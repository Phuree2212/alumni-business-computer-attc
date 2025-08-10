<?php

/**
 * Image Upload Helper Class
 * สำหรับจัดการการอัปโหลดรูปภาพอย่างปลอดภัย
 */
class ImageUploader
{

    private $allowedTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp'
    ];

    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
    private $maxFileSize = 5242880; // 5MB in bytes
    private $maxFiles = 5;
    private $uploadDir = '';
    private $createThumbnail = false;
    private $thumbnailWidth = 200;
    private $thumbnailHeight = 200;

    public function __construct($uploadDir = 'uploads/')
    {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
    }

    /**
     * ตั้งค่าประเภทไฟล์ที่อนุญาต
     */
    public function setAllowedTypes($types)
    {
        $this->allowedTypes = $types;
        return $this;
    }

    /**
     * ตั้งค่าขนาดไฟล์สูงสุด (หน่วยเป็น bytes)
     */
    public function setMaxFileSize($size)
    {
        $this->maxFileSize = $size;
        return $this;
    }

    /**
     * ตั้งค่าจำนวนไฟล์สูงสุด
     */
    public function setMaxFiles($count)
    {
        $this->maxFiles = $count;
        return $this;
    }

    /**
     * เปิดใช้งานการสร้าง thumbnail
     */
    public function enableThumbnail($width = 200, $height = 200)
    {
        $this->createThumbnail = true;
        $this->thumbnailWidth = $width;
        $this->thumbnailHeight = $height;
        return $this;
    }

    /**
     * อัปโหลดรูปภาพเดียว
     */
    public function uploadSingle($fileInput, $customName = null)
    {
        try {
            if (!isset($fileInput) || $fileInput['error'] === UPLOAD_ERR_NO_FILE) {
                return ['success' => false, 'message' => 'ไม่มีไฟล์ที่อัปโหลด'];
            }

            if ($fileInput['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => $this->getUploadError($fileInput['error'])];
            }

            $validation = $this->validateFile($fileInput);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }

            if (!$this->createDirectory($this->uploadDir)) {
                return ['success' => false, 'message' => 'ไม่สามารถสร้างโฟลเดอร์ได้'];
            }

            $fileName = $this->generateFileName($fileInput['name'], $customName);
            $filePath = $this->uploadDir . $fileName;
            $tempResizedPath = $this->uploadDir . 'resized_' . uniqid() . '_' . basename($fileName);

            // ย่อขนาดรูปก่อนอัปโหลด
            if ($this->resizeImageBeforeUpload($fileInput['tmp_name'], $tempResizedPath)) {
                if (rename($tempResizedPath, $filePath)) {
                    $result = [
                        'success' => true,
                        'fileName' => $fileName,
                        'filePath' => $filePath,
                        'fileSize' => filesize($filePath),
                        'fileType' => $fileInput['type']
                    ];

                    if ($this->createThumbnail) {
                        $thumbnailPath = $this->createThumbnailImage($filePath, $fileName);
                        if ($thumbnailPath) {
                            $result['thumbnailPath'] = $thumbnailPath;
                        }
                    }

                    return $result;
                } else {
                    @unlink($tempResizedPath);
                    return ['success' => false, 'message' => 'ไม่สามารถย้ายไฟล์ที่ย่อแล้วได้'];
                }
            } else {
                echo "<script>alert('ไม่สามารถย่อรูปภาพได้')</script>";
                // ถ้าย่อรูปไม่ได้ fallback เป็น move_uploaded_file
                if (move_uploaded_file($fileInput['tmp_name'], $filePath)) {
                    $result = [
                        'success' => true,
                        'fileName' => $fileName,
                        'filePath' => $filePath,
                        'fileSize' => $fileInput['size'],
                        'fileType' => $fileInput['type']
                    ];

                    /*
                    if ($this->createThumbnail) {
                        $thumbnailPath = $this->createThumbnailImage($filePath, $fileName);
                        if ($thumbnailPath) {
                            $result['thumbnailPath'] = $thumbnailPath;
                        }
                    }*/

                    return $result;
                } else {
                    return ['success' => false, 'message' => 'ไม่สามารถอัปโหลดไฟล์ได้'];
                }
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()];
        }
    }


    /**
     * อัปโหลดรูปภาพหลายรูป
     */
    public function uploadMultiple($filesInput, $prefix = null)
    {
        try {
            $results = [];
            $errors = [];

            if (!isset($filesInput['name']) || empty($filesInput['name'][0])) {
                return ['success' => false, 'message' => 'ไม่มีไฟล์ที่อัปโหลด'];
            }

            $fileCount = count($filesInput['name']);

            if ($fileCount > $this->maxFiles) {
                return ['success' => false, 'message' => "สามารถอัปโหลดได้สูงสุด {$this->maxFiles} ไฟล์"];
            }

            if (!$this->createDirectory($this->uploadDir)) {
                return ['success' => false, 'message' => 'ไม่สามารถสร้างโฟลเดอร์ได้'];
            }

            for ($i = 0; $i < $fileCount; $i++) {
                $file = [
                    'name' => $filesInput['name'][$i],
                    'type' => $filesInput['type'][$i],
                    'tmp_name' => $filesInput['tmp_name'][$i],
                    'error' => $filesInput['error'][$i],
                    'size' => $filesInput['size'][$i]
                ];

                if (empty($file['name'])) continue;

                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $errors[] = $file['name'] . ': ' . $this->getUploadError($file['error']);
                    continue;
                }

                $validation = $this->validateFile($file);
                if (!$validation['valid']) {
                    $errors[] = $file['name'] . ': ' . $validation['message'];
                    continue;
                }

                $customName = $prefix ? $prefix . '_' . ($i + 1) : null;
                $fileName = $this->generateFileName($file['name'], $customName);
                $filePath = $this->uploadDir . $fileName;
                $tempResizedPath = $this->uploadDir . 'resized_' . uniqid() . '_' . basename($fileName);

                // ย่อรูปภาพก่อนอัปโหลด
                if ($this->resizeImageBeforeUpload($file['tmp_name'], $tempResizedPath)) {
                    if (rename($tempResizedPath, $filePath)) {
                        $fileResult = [
                            'originalName' => $file['name'],
                            'fileName' => $fileName,
                            'filePath' => $filePath,
                            'fileSize' => filesize($filePath),
                            'fileType' => $file['type']
                        ];
                        $results[] = $fileResult;
                    } else {
                        $errors[] = $file['name'] . ': ไม่สามารถย้ายไฟล์ที่ย่อแล้วได้';
                        @unlink($tempResizedPath);
                    }
                } else {
                    // ถ้าย่อไม่สำเร็จ fallback กลับไปใช้ move_uploaded_file
                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        $fileResult = [
                            'originalName' => $file['name'],
                            'fileName' => $fileName,
                            'filePath' => $filePath,
                            'fileSize' => $file['size'],
                            'fileType' => $file['type']
                        ];
                        $results[] = $fileResult;
                    } else {
                        $errors[] = $file['name'] . ': ไม่สามารถอัปโหลดได้';
                    }
                }
            }

            return [
                'success' => !empty($results),
                'files' => $results,
                'errors' => $errors,
                'uploadedCount' => count($results),
                'errorCount' => count($errors)
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()];
        }
    }

    //ลดขนาดรูปภาพ
    private function resizeImageBeforeUpload($sourcePath, $destinationPath, $maxWidth = 1024, $maxHeight = 1024)
    {
        if (!file_exists($sourcePath)) return false;

        list($width, $height, $type) = getimagesize($sourcePath);
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        if ($ratio > 1) {
            $ratio = 1; // ไม่ขยายภาพ
        }

        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $srcImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $srcImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $srcImage = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }

        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagecolortransparent($dstImage, imagecolorallocatealpha($dstImage, 0, 0, 0, 127));
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($dstImage, $destinationPath, 50); // ลองปรับเป็น 40 หรือ 60 ได้
                break;
            case IMAGETYPE_PNG:
                imagepng($dstImage, $destinationPath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($dstImage, $destinationPath);
                break;
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return true;
    }



    /**
     * ตรวจสอบความถูกต้องของไฟล์
     */
    private function validateFile($file)
    {
        // ตรวจสอบขนาดไฟล์
        if ($file['size'] > $this->maxFileSize) {
            return [
                'valid' => false,
                'message' => 'ขนาดไฟล์เกิน ' . $this->formatBytes($this->maxFileSize)
            ];
        }

        // ตรวจสอบประเภทไฟล์
        if (!in_array($file['type'], $this->allowedTypes)) {
            return [
                'valid' => false,
                'message' => 'ประเภทไฟล์ไม่ถูกต้อง รองรับเฉพาะ: ' . implode(', ', $this->allowedExtensions)
            ];
        }

        // ตรวจสอบนามสกุลไฟล์
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'นามสกุลไฟล์ไม่ถูกต้อง'
            ];
        }

        // ตรวจสอบว่าเป็นไฟล์รูปภาพจริง
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return [
                'valid' => false,
                'message' => 'ไฟล์ไม่ใช่รูปภาพ'
            ];
        }

        return ['valid' => true];
    }

    /**
     * สร้างชื่อไฟล์ใหม่
     */
    private function generateFileName($originalName, $customName = null)
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($customName) {
            return $customName . '_' . time() . '.' . $extension;
        }

        return uniqid() . '_' . time() . '.' . $extension;
    }

    /**
     * สร้างโฟลเดอร์
     */
    private function createDirectory($path)
    {
        if (!is_dir($path)) {
            return mkdir($path, 0755, true);
        }
        return true;
    }

    /**
     * สร้าง thumbnail
     */
    private function createThumbnailImage($sourcePath, $fileName)
    {
        try {
            $thumbnailDir = $this->uploadDir . 'thumbnails/';
            if (!$this->createDirectory($thumbnailDir)) {
                return false;
            }

            $imageInfo = getimagesize($sourcePath);
            if (!$imageInfo) return false;

            $sourceWidth = $imageInfo[0];
            $sourceHeight = $imageInfo[1];
            $mimeType = $imageInfo['mime'];

            // คำนวณขนาดใหม่โดยรักษาสัดส่วน
            $ratio = min($this->thumbnailWidth / $sourceWidth, $this->thumbnailHeight / $sourceHeight);
            $newWidth = intval($sourceWidth * $ratio);
            $newHeight = intval($sourceHeight * $ratio);

            // สร้าง image resource
            $sourceImage = $this->createImageResource($sourcePath, $mimeType);
            if (!$sourceImage) return false;

            // สร้าง thumbnail
            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

            // รักษาความโปร่งใสสำหรับ PNG และ GIF
            if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
                $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
                imagefill($thumbnail, 0, 0, $transparent);
            }

            imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

            // บันทึก thumbnail
            $thumbnailPath = $thumbnailDir . 'thumb_' . $fileName;
            $saved = $this->saveImageResource($thumbnail, $thumbnailPath, $mimeType);

            // ล้าง memory
            imagedestroy($sourceImage);
            imagedestroy($thumbnail);

            return $saved ? $thumbnailPath : false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * สร้าง image resource จากไฟล์
     */
    private function createImageResource($path, $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            case 'image/bmp':
                return imagecreatefrombmp($path);
            default:
                return false;
        }
    }

    /**
     * บันทึก image resource เป็นไฟล์
     */
    private function saveImageResource($image, $path, $mimeType, $quality = 85)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagejpeg($image, $path, $quality);
            case 'image/png':
                return imagepng($image, $path, 9);
            case 'image/gif':
                return imagegif($image, $path);
            case 'image/webp':
                return imagewebp($image, $path, $quality);
            case 'image/bmp':
                return imagebmp($image, $path);
            default:
                return false;
        }
    }

    /**
     * ลบไฟล์
     */
    public function deleteFile($fileName)
    {
        $filePath = $this->uploadDir . $fileName;
        if (file_exists($filePath)) {
            unlink($filePath);

            // ลบ thumbnail ด้วย
            /*$thumbnailPath = $this->uploadDir . 'thumbnails/thumb_' . $fileName;
            if (file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }*/

            return true;
        }
        return false;
    }

    /**
     * ลบไฟล์หลายรูป
     */
    public function deleteMultipleFiles($fileNames)
    {
        $deleted = 0;
        foreach ($fileNames as $fileName) {
            if ($this->deleteFile($fileName)) {
                $deleted++;
            }
        }
        return $deleted;
    }

    /**
     * แปลงข้อผิดพลาดการอัปโหลด
     */
    private function getUploadError($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'ขนาดไฟล์เกินกำหนดของเซิร์ฟเวอร์';
            case UPLOAD_ERR_FORM_SIZE:
                return 'ขนาดไฟล์เกินกำหนดของฟอร์ม';
            case UPLOAD_ERR_PARTIAL:
                return 'อัปโหลดไฟล์ไม่สมบูรณ์';
            case UPLOAD_ERR_NO_FILE:
                return 'ไม่มีไฟล์ที่อัปโหลด';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'ไม่มีโฟลเดอร์ชั่วคราว';
            case UPLOAD_ERR_CANT_WRITE:
                return 'ไม่สามารถเขียนไฟล์ได้';
            case UPLOAD_ERR_EXTENSION:
                return 'การอัปโหลดถูกหยุดโดย extension';
            default:
                return 'ข้อผิดพลาดที่ไม่ทราบสาเหตุ';
        }
    }

    /**
     * แปลงขนาดไฟล์เป็นรูปแบบที่อ่านง่าย
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * ตรวจสอบว่าไฟล์เป็นรูปภาพหรือไม่
     */
    public static function isValidImage($filePath)
    {
        $imageInfo = getimagesize($filePath);
        return $imageInfo !== false;
    }

    /**
     * รีไซซ์รูปภาพ
     */
    public function resizeImage($sourcePath, $targetPath, $maxWidth, $maxHeight, $quality = 85)
    {
        try {
            $imageInfo = getimagesize($sourcePath);
            if (!$imageInfo) return false;

            $sourceWidth = $imageInfo[0];
            $sourceHeight = $imageInfo[1];
            $mimeType = $imageInfo['mime'];

            // คำนวณขนาดใหม่
            $ratio = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight);
            $newWidth = intval($sourceWidth * $ratio);
            $newHeight = intval($sourceHeight * $ratio);

            // สร้าง image resource
            $sourceImage = $this->createImageResource($sourcePath, $mimeType);
            if (!$sourceImage) return false;

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // รักษาความโปร่งใส
            if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefill($resizedImage, 0, 0, $transparent);
            }

            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

            // บันทึก
            $saved = $this->saveImageResource($resizedImage, $targetPath, $mimeType, $quality);

            // ล้าง memory
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);

            return $saved;
        } catch (Exception $e) {
            return false;
        }
    }
}
