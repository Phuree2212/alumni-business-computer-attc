<?php
/**
 * Pagination Helper Class
 * ใช้สำหรับจัดการ pagination ในระบบ
 */
class PaginationHelper {
    private $currentPage;
    private $itemsPerPage;
    private $totalItems;
    private $totalPages;
    private $baseUrl;
    private $queryParams;

    public function __construct($currentPage = 1, $itemsPerPage = 10, $totalItems = 0, $baseUrl = '') {
        $this->currentPage = max(1, (int)$currentPage);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->totalItems = max(0, (int)$totalItems);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        $this->baseUrl = $baseUrl ?: $this->getCurrentUrl();
        $this->queryParams = $_GET;
    }

    /**
     * คำนวณ OFFSET สำหรับ SQL LIMIT
     */
    public function getOffset() {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    /**
     * คำนวณ LIMIT สำหรับ SQL
     */
    public function getLimit() {
        return $this->itemsPerPage;
    }

    /**
     * ตรวจสอบว่ามีหน้าถัดไปหรือไม่
     */
    public function hasNextPage() {
        return $this->currentPage < $this->totalPages;
    }

    /**
     * ตรวจสอบว่ามีหน้าก่อนหน้าหรือไม่
     */
    public function hasPreviousPage() {
        return $this->currentPage > 1;
    }

    /**
     * สร้าง URL สำหรับหน้าต่างๆ
     */
    public function getPageUrl($page) {
        $params = $this->queryParams;
        $params['page'] = $page;
        return $this->baseUrl . '?' . http_build_query($params);
    }

    /**
     * ดึง URL หน้าถัดไป
     */
    public function getNextPageUrl() {
        return $this->hasNextPage() ? $this->getPageUrl($this->currentPage + 1) : null;
    }

    /**
     * ดึง URL หน้าก่อนหน้า
     */
    public function getPreviousPageUrl() {
        return $this->hasPreviousPage() ? $this->getPageUrl($this->currentPage - 1) : null;
    }

    /**
     * ดึง URL หน้าแรก
     */
    public function getFirstPageUrl() {
        return $this->getPageUrl(1);
    }

    /**
     * ดึง URL หน้าสุดท้าย
     */
    public function getLastPageUrl() {
        return $this->getPageUrl($this->totalPages);
    }

    /**
     * สร้างข้อมูล pagination สำหรับ API
     */
    public function toArray() {
        return [
            'current_page' => $this->currentPage,
            'per_page' => $this->itemsPerPage,
            'total_items' => $this->totalItems,
            'total_pages' => $this->totalPages,
            'has_next' => $this->hasNextPage(),
            'has_previous' => $this->hasPreviousPage(),
            'next_page_url' => $this->getNextPageUrl(),
            'previous_page_url' => $this->getPreviousPageUrl(),
            'first_page_url' => $this->getFirstPageUrl(),
            'last_page_url' => $this->getLastPageUrl(),
            'from' => $this->getOffset() + 1,
            'to' => min($this->getOffset() + $this->itemsPerPage, $this->totalItems),
        ];
    }

    /**
     * สร้าง HTML pagination แบบ Bootstrap พร้อมข้อมูล
     */
    public function renderBootstrap($showNumbers = 5, $useJavaScript = false) {
       

        $from = $this->getOffset() + 1;
        $to = min($this->getOffset() + $this->itemsPerPage, $this->totalItems);
        
        $html = '<div class="row align-items-center">';
        
        // Results info section
        $html .= '<div class="col-md-6">';
        $html .= '<div class="results-info">';
        $html .= "แสดง <span id=\"showingStart\">{$from}</span> ถึง <span id=\"showingEnd\">{$to}</span> จาก <span id=\"totalResults\">{$this->totalItems}</span> รายการ";
        $html .= '</div>';
        $html .= '</div>';
        
        // Pagination section
        $html .= '<div class="col-md-6">';
        $html .= '<nav aria-label="Page navigation">';
        $html .= '<ul class="pagination justify-content-end" id="pagination">';

        // First page button
        if ($this->hasPreviousPage()) {
            if ($useJavaScript) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="#" onclick="changePage(1)">';
                $html .= '<i class="fas fa-angle-double-left"></i>';
                $html .= '</a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . $this->getFirstPageUrl() . '">';
                $html .= '<i class="fas fa-angle-double-left"></i>';
                $html .= '</a>';
                $html .= '</li>';
            }
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<a class="page-link" href="#" onclick="changePage(1)">';
            $html .= '<i class="fas fa-angle-double-left"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }

        // Previous page button
        if ($this->hasPreviousPage()) {
            if ($useJavaScript) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="#" onclick="changePage(\'prev\')">';
                $html .= '<i class="fas fa-angle-left"></i>';
                $html .= '</a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . $this->getPreviousPageUrl() . '">';
                $html .= '<i class="fas fa-angle-left"></i>';
                $html .= '</a>';
                $html .= '</li>';
            }
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<a class="page-link" href="#" onclick="changePage(\'prev\')">';
            $html .= '<i class="fas fa-angle-left"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }

        // Page numbers
        $start = max(1, $this->currentPage - floor($showNumbers / 2));
        $end = min($this->totalPages, $start + $showNumbers - 1);

        // Adjust start if we're near the end
        if ($end - $start + 1 < $showNumbers) {
            $start = max(1, $end - $showNumbers + 1);
        }

        // First page and dots
        if ($start > 1) {
            if ($useJavaScript) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="#" onclick="changePage(1)">1</a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . $this->getFirstPageUrl() . '">1</a>';
                $html .= '</li>';
            }
            
            if ($start > 2) {
                $html .= '<li class="page-item disabled">';
                $html .= '<span class="page-link">...</span>';
                $html .= '</li>';
            }
        }

        // Number pages
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->currentPage) {
                $html .= '<li class="page-item active">';
                if ($useJavaScript) {
                    $html .= '<a class="page-link" href="#" onclick="changePage(' . $i . ')">' . $i . '</a>';
                } else {
                    $html .= '<span class="page-link">' . $i . '</span>';
                }
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item">';
                if ($useJavaScript) {
                    $html .= '<a class="page-link" href="#" onclick="changePage(' . $i . ')">' . $i . '</a>';
                } else {
                    $html .= '<a class="page-link" href="' . $this->getPageUrl($i) . '">' . $i . '</a>';
                }
                $html .= '</li>';
            }
        }

        // Last page and dots
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $html .= '<li class="page-item disabled">';
                $html .= '<span class="page-link">...</span>';
                $html .= '</li>';
            }
            
            if ($useJavaScript) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="#" onclick="changePage(' . $this->totalPages . ')">' . $this->totalPages . '</a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . $this->getLastPageUrl() . '">' . $this->totalPages . '</a>';
                $html .= '</li>';
            }
        }

        // Next page button
        if ($this->hasNextPage()) {
            if ($useJavaScript) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="#" onclick="changePage(\'next\')">';
                $html .= '<i class="fas fa-angle-right"></i>';
                $html .= '</a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . $this->getNextPageUrl() . '">';
                $html .= '<i class="fas fa-angle-right"></i>';
                $html .= '</a>';
                $html .= '</li>';
            }
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<a class="page-link" href="#" onclick="changePage(\'next\')">';
            $html .= '<i class="fas fa-angle-right"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }

        // Last page button
        if ($this->hasNextPage()) {
            if ($useJavaScript) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="#" onclick="changePage(\'last\')">';
                $html .= '<i class="fas fa-angle-double-right"></i>';
                $html .= '</a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . $this->getLastPageUrl() . '">';
                $html .= '<i class="fas fa-angle-double-right"></i>';
                $html .= '</a>';
                $html .= '</li>';
            }
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<a class="page-link" href="#" onclick="changePage(\'last\')">';
            $html .= '<i class="fas fa-angle-double-right"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</nav>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * สร้าง HTML pagination
     */
    public function render($showNumbers = 5) {
        if ($this->totalPages <= 1) {
            return '';
        }

        $html = '<nav aria-label="Pagination">';
        $html .= '<ul class="pagination">';

        // Previous button
        if ($this->hasPreviousPage()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getPreviousPageUrl() . '">Previous</a>';
            $html .= '</li>';
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<span class="page-link">Previous</span>';
            $html .= '</li>';
        }

        // Page numbers
        $start = max(1, $this->currentPage - floor($showNumbers / 2));
        $end = min($this->totalPages, $start + $showNumbers - 1);

        // Adjust start if we're near the end
        if ($end - $start + 1 < $showNumbers) {
            $start = max(1, $end - $showNumbers + 1);
        }

        // First page and dots
        if ($start > 1) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getFirstPageUrl() . '">1</a>';
            $html .= '</li>';
            
            if ($start > 2) {
                $html .= '<li class="page-item disabled">';
                $html .= '<span class="page-link">...</span>';
                $html .= '</li>';
            }
        }

        // Number pages
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->currentPage) {
                $html .= '<li class="page-item active">';
                $html .= '<span class="page-link">' . $i . '</span>';
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="' . $this->getPageUrl($i) . '">' . $i . '</a>';
                $html .= '</li>';
            }
        }

        // Last page and dots
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $html .= '<li class="page-item disabled">';
                $html .= '<span class="page-link">...</span>';
                $html .= '</li>';
            }
            
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getLastPageUrl() . '">' . $this->totalPages . '</a>';
            $html .= '</li>';
        }

        // Next button
        if ($this->hasNextPage()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getNextPageUrl() . '">Next</a>';
            $html .= '</li>';
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<span class="page-link">Next</span>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</nav>';

        return $html;
    }

    /**
     * สร้าง pagination แบบง่าย (เฉพาะ Previous/Next)
     */
    public function renderSimple() {
        $html = '<nav aria-label="Simple Pagination">';
        $html .= '<ul class="pagination-simple">';

        if ($this->hasPreviousPage()) {
            $html .= '<li>';
            $html .= '<a href="' . $this->getPreviousPageUrl() . '">← Previous</a>';
            $html .= '</li>';
        }

        $html .= '<li class="current-page">';
        $html .= '<span>Page ' . $this->currentPage . ' of ' . $this->totalPages . '</span>';
        $html .= '</li>';

        if ($this->hasNextPage()) {
            $html .= '<li>';
            $html .= '<a href="' . $this->getNextPageUrl() . '">Next →</a>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</nav>';

        return $html;
    }

    /**
     * สร้าง pagination info text
     */
    public function getInfoText() {
        $from = $this->getOffset() + 1;
        $to = min($this->getOffset() + $this->itemsPerPage, $this->totalItems);
        
        return "Showing {$from} to {$to} of {$this->totalItems} results";
    }

    /**
     * ดึง URL ปัจจุบัน
     */
    private function getCurrentUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $protocol . '://' . $host . $uri;
    }

    // Getters
    public function getCurrentPage() { return $this->currentPage; }
    public function getItemsPerPage() { return $this->itemsPerPage; }
    public function getTotalItems() { return $this->totalItems; }
    public function getTotalPages() { return $this->totalPages; }
}

/**
 * ฟังก์ชันสำหรับสร้าง pagination อย่างง่าย
 */
function createPagination($currentPage, $itemsPerPage, $totalItems, $baseUrl = '') {
    return new PaginationHelper($currentPage, $itemsPerPage, $totalItems, $baseUrl);
}

/**
 * ฟังก์ชันสำหรับคำนวณ pagination data
 */
function calculatePagination($page, $perPage, $total) {
    $page = max(1, (int)$page);
    $perPage = max(1, (int)$perPage);
    $total = max(0, (int)$total);
    
    $totalPages = ceil($total / $perPage);
    $offset = ($page - 1) * $perPage;
    
    return [
        'current_page' => $page,
        'per_page' => $perPage,
        'total_items' => $total,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'limit' => $perPage,
        'has_next' => $page < $totalPages,
        'has_previous' => $page > 1,
        'from' => $offset + 1,
        'to' => min($offset + $perPage, $total)
    ];
}

// ตัวอย่างการใช้งาน
/*
// ในไฟล์ Controller หรือ View
$currentPage = $_GET['page'] ?? 1;
$itemsPerPage = 10;
$totalItems = 150; // จำนวนรายการทั้งหมดจากฐานข้อมูล

// สร้าง pagination
$pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

// ใช้กับ SQL Query
$offset = $pagination->getOffset();
$limit = $pagination->getLimit();
$sql = "SELECT * FROM news ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}";

// แสดง pagination HTML แบบใหม่
echo $pagination->renderBootstrap(); // ใช้ JavaScript
// หรือ
echo $pagination->renderBootstrap(5, false); // ใช้ URL links

// แสดง pagination HTML แบบเดิม
echo $pagination->render();
echo $pagination->getInfoText();

// สำหรับ API
$paginationData = $pagination->toArray();
echo json_encode([
    'data' => $results,
    'pagination' => $paginationData
]);
*/
?>