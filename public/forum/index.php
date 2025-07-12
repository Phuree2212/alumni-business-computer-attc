<?php
require_once '../../config/config.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
  <?php include '../../includes/title.php' ?>
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<style>
  .forum-container {
    min-height: calc(100vh - 3.5rem);
  }

  .sidebar-nav {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
    padding: 1.5rem;
  }

  .main-content {
    background-color: #f8f9fa;
    min-height: calc(100vh - 3.5rem);
  }

  .forum-header {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
  }

  .forum-card {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
  }

  .forum-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px 0 rgba(0, 0, 0, .15);
  }

  .nav-pills .nav-link.active {
    background-color: #467bcb;
    color: #fff;
  }

  .nav-pills .nav-link {
    color: #4a5568;
    border-radius: 0.375rem;
    margin-bottom: 0.5rem;
  }

  .nav-pills .nav-link:hover {
    background-color: #e2e8f0;
  }

  .forum-avatar {
    width: 50px;
    height: 50px;
    object-fit: cover;
  }

  .forum-stats {
    display: flex;
    gap: 1rem;
    align-items: center;
    font-size: 0.875rem;
    color: #6b7280;
  }

  .forum-title {
    color: #1f2937;
    text-decoration: none;
    font-weight: 600;
  }

  .forum-title:hover {
    color: #467bcb;
    text-decoration: none;
  }

  .forum-description {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
  }

  .forum-meta {
    color: #9ca3af;
    font-size: 0.8125rem;
  }

  .btn-new-discussion {
    background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
    border: none;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    box-shadow: 0 2px 4px rgba(70, 123, 203, 0.3);
  }

  .btn-new-discussion:hover {
    background: linear-gradient(135deg, #3a6bb0 0%, #4d7dc5 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(70, 123, 203, 0.4);
  }

  /* Responsive adjustments */
  @media (max-width: 767.98px) {
    .main-content {
      padding: 1rem;
    }

    .forum-header {
      padding: 0.75rem 1rem;
      margin-bottom: 1rem;
    }

    .sidebar-nav {
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .forum-stats {
      gap: 0.5rem;
      font-size: 0.8125rem;
    }

    .forum-avatar {
      width: 40px;
      height: 40px;
    }
  }

  @media (max-width: 575.98px) {
    .forum-card .card-body {
      padding: 1rem;
    }

    .forum-stats span:first-child {
      display: none !important;
    }
  }

  /* Accordion styles for responsive sidebar */
  .accordion-button {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
    font-weight: 600;
  }

  .accordion-button:not(.collapsed) {
    background-color: #467bcb;
    color: white;
    border-color: #467bcb;
  }

  .accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(70, 123, 203, 0.25);
  }

  .accordion-button::after {
    filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);
  }

  .accordion-button:not(.collapsed)::after {
    filter: brightness(0) saturate(100%) invert(100%);
  }

  .accordion-body .nav-link {
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.25rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
  }

  @media (max-width: 991.98px) {
    .accordion-item {
      border-radius: 0.5rem;
      overflow: hidden;
      margin-bottom: 0.5rem;
    }

    .accordion-item:last-child {
      margin-bottom: 0;
    }
  }

  .pagination-custom .page-link {
    border: none;
    color: #467bcb;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border-radius: 0.375rem;
  }

  .pagination-custom .page-item.active .page-link {
    background-color: #467bcb;
    color: #fff;
  }

  .pagination-custom .page-link:hover {
    background-color: #e2e8f0;
    color: #467bcb;
  }

  /* ปรับให้ modal อยู่ตรงกลางหน้าจอ */
  .modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
  }

  /* เพิ่มการตอบสนองสำหรับหน้าจอขนาดเล็ก */
  @media (max-width: 576px) {
    .modal-dialog {
      margin: 0.5rem;
      max-width: calc(100% - 1rem);
    }
  }

  /* ปรับแต่งปุ่มอัพโหลดไฟล์ */
  .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  /* เพิ่มเอฟเฟกต์เมื่อ modal เปิด */
  .modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: scale(0.8);
  }

  .modal.show .modal-dialog {
    transform: scale(1);
  }
</style>

<body>
  <?php include '../../includes/navbar.php' ?>

  <div class="container px-4">
    <div class="row forum-container p-3">
      <!-- Sidebar -->
      <div class="col-lg-3 col-md-4">
        <!-- Desktop Sidebar -->
        <div class="d-none d-lg-block">
          <div class="sidebar-nav">
            <!-- New Discussion Button -->
            <button class="btn btn-primary btn-new-discussion w-100 mb-4" type="button" data-bs-toggle="modal" data-bs-target="#threadModal">
              <i class="fas fa-plus me-2"></i>
              NEW DISCUSSION
            </button>

            <!-- Navigation Menu -->
            <nav class="nav nav-pills flex-column">
              <a href="javascript:void(0)" class="nav-link active">
                <i class="fas fa-list me-2"></i>All Threads
              </a>
              <a href="javascript:void(0)" class="nav-link">
                <i class="fas fa-fire me-2"></i>Popular this week
              </a>
              <a href="javascript:void(0)" class="nav-link">
                <i class="fas fa-trophy me-2"></i>Popular all time
              </a>
              <a href="javascript:void(0)" class="nav-link">
                <i class="fas fa-check-circle me-2"></i>Solved
              </a>
              <a href="javascript:void(0)" class="nav-link">
                <i class="fas fa-question-circle me-2"></i>Unsolved
              </a>
              <a href="javascript:void(0)" class="nav-link">
                <i class="fas fa-clock me-2"></i>No replies yet
              </a>
            </nav>
          </div>
        </div>

        <!-- Mobile/Tablet Accordion -->
        <div class="d-lg-none mb-3">
          <div class="accordion" id="sidebarAccordion">
            <!-- Navigation Accordion Item -->
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNavigation" aria-expanded="true" aria-controls="collapseNavigation">
                  <i class="fas fa-filter me-2"></i>
                  Forum Categories
                </button>
              </h2>
              <div id="collapseNavigation" class="accordion-collapse collapse show" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-2">
                  <nav class="nav nav-pills flex-column">
                    <a href="javascript:void(0)" class="nav-link active">
                      <i class="fas fa-list me-2"></i>All Threads
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-fire me-2"></i>Popular this week
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-trophy me-2"></i>Popular all time
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-check-circle me-2"></i>Solved
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-question-circle me-2"></i>Unsolved
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-clock me-2"></i>No replies yet
                    </a>
                  </nav>
                </div>
              </div>
            </div>

            <!-- Quick Actions Accordion Item -->
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseActions" aria-expanded="false" aria-controls="collapseActions">
                  <i class="fas fa-bolt me-2"></i>
                  Quick Actions
                </button>
              </h2>
              <div id="collapseActions" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-2">
                  <button class="btn btn-primary btn-new-discussion w-100 mb-2" type="button" data-bs-toggle="modal" data-bs-target="#threadModal">
                    <i class="fas fa-plus me-2"></i>
                    NEW DISCUSSION
                  </button>
                  <button class="btn btn-outline-secondary w-100 mb-2" type="button">
                    <i class="fas fa-bookmark me-2"></i>
                    My Bookmarks
                  </button>
                  <button class="btn btn-outline-secondary w-100" type="button">
                    <i class="fas fa-user-edit me-2"></i>
                    My Posts
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9 col-md-8">
        <div class="main-content">
          <!-- Header Controls -->
          <div class="forum-header">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="d-flex align-items-center">
                  <select class="form-select form-select-sm me-3" style="width: auto;">
                    <option selected>Latest</option>
                    <option value="1">Popular</option>
                    <option value="2">Solved</option>
                    <option value="3">Unsolved</option>
                    <option value="4">No Replies Yet</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex justify-content-md-end">
                  <input type="text" class="form-control form-control-sm" style="max-width: 250px;" placeholder="Search forum..." />
                </div>
              </div>
            </div>
          </div>

          <!-- Forum List -->
          <div class="forum-content" id="forumList">
            <div class="row g-3">
              <!-- Forum Item 1 -->
              <div class="col-12">
                <div class="card forum-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle forum-avatar" alt="User" />
                      </div>
                      <div class="col">
                        <h6 class="mb-1">
                          <a href="#" class="forum-title" onclick="showForumDetail()">Realtime fetching data</a>
                        </h6>
                        <p class="forum-description mb-2">
                          lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet
                        </p>
                        <div class="forum-meta">
                          <a href="javascript:void(0)" class="text-decoration-none">drewdan</a> replied
                          <span class="fw-bold">13 minutes ago</span>
                        </div>
                      </div>
                      <div class="col-auto">
                        <div class="forum-stats">
                          <span><i class="far fa-eye"></i> 19</span>
                          <span><i class="far fa-comment"></i> 3</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Forum Item 2 -->
              <div class="col-12">
                <div class="card forum-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="rounded-circle forum-avatar" alt="User" />
                      </div>
                      <div class="col">
                        <h6 class="mb-1">
                          <a href="#" class="forum-title" onclick="showForumDetail()">Laravel 7 database backup</a>
                        </h6>
                        <p class="forum-description mb-2">
                          lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet
                        </p>
                        <div class="forum-meta">
                          <a href="javascript:void(0)" class="text-decoration-none">jlrdw</a> replied
                          <span class="fw-bold">3 hours ago</span>
                        </div>
                      </div>
                      <div class="col-auto">
                        <div class="forum-stats">
                          <span><i class="far fa-eye"></i> 18</span>
                          <span><i class="far fa-comment"></i> 1</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Forum Item 3 -->
              <div class="col-12">
                <div class="card forum-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle forum-avatar" alt="User" />
                      </div>
                      <div class="col">
                        <h6 class="mb-1">
                          <a href="#" class="forum-title" onclick="showForumDetail()">Http client post raw content</a>
                        </h6>
                        <p class="forum-description mb-2">
                          lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet
                        </p>
                        <div class="forum-meta">
                          <a href="javascript:void(0)" class="text-decoration-none">ciungulete</a> replied
                          <span class="fw-bold">7 hours ago</span>
                        </div>
                      </div>
                      <div class="col-auto">
                        <div class="forum-stats">
                          <span><i class="far fa-eye"></i> 32</span>
                          <span><i class="far fa-comment"></i> 2</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Forum Item 4 -->
              <div class="col-12">
                <div class="card forum-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <img src="https://bootdey.com/img/Content/avatar/avatar4.png" class="rounded-circle forum-avatar" alt="User" />
                      </div>
                      <div class="col">
                        <h6 class="mb-1">
                          <a href="#" class="forum-title" onclick="showForumDetail()">Top rated filter not working</a>
                        </h6>
                        <p class="forum-description mb-2">
                          lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet
                        </p>
                        <div class="forum-meta">
                          <a href="javascript:void(0)" class="text-decoration-none">bugsysha</a> replied
                          <span class="fw-bold">11 hours ago</span>
                        </div>
                      </div>
                      <div class="col-auto">
                        <div class="forum-stats">
                          <span><i class="far fa-eye"></i> 49</span>
                          <span><i class="far fa-comment"></i> 9</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Forum Item 5 -->
              <div class="col-12">
                <div class="card forum-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="rounded-circle forum-avatar" alt="User" />
                      </div>
                      <div class="col">
                        <h6 class="mb-1">
                          <a href="#" class="forum-title" onclick="showForumDetail()">Create a delimiter field</a>
                        </h6>
                        <p class="forum-description mb-2">
                          lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet
                        </p>
                        <div class="forum-meta">
                          <a href="javascript:void(0)" class="text-decoration-none">jackalds</a> replied
                          <span class="fw-bold">12 hours ago</span>
                        </div>
                      </div>
                      <div class="col-auto">
                        <div class="forum-stats">
                          <span><i class="far fa-eye"></i> 65</span>
                          <span><i class="far fa-comment"></i> 10</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Forum Item 6 -->
              <div class="col-12">
                <div class="card forum-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle forum-avatar" alt="User" />
                      </div>
                      <div class="col">
                        <h6 class="mb-1">
                          <a href="#" class="forum-title" onclick="showForumDetail()">One model 4 tables</a>
                        </h6>
                        <p class="forum-description mb-2">
                          lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet
                        </p>
                        <div class="forum-meta">
                          <a href="javascript:void(0)" class="text-decoration-none">bugsysha</a> replied
                          <span class="fw-bold">14 hours ago</span>
                        </div>
                      </div>
                      <div class="col-auto">
                        <div class="forum-stats">
                          <span><i class="far fa-eye"></i> 45</span>
                          <span><i class="far fa-comment"></i> 4</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Forum Item 7 -->
              <div class="col-12">
                <div class="card forum-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle forum-avatar" alt="User" />
                      </div>
                      <div class="col">
                        <h6 class="mb-1">
                          <a href="#" class="forum-title" onclick="showForumDetail()">Auth attempt returns false</a>
                        </h6>
                        <p class="forum-description mb-2">
                          lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet
                        </p>
                        <div class="forum-meta">
                          <a href="javascript:void(0)" class="text-decoration-none">michaeloravec</a> replied
                          <span class="fw-bold">18 hours ago</span>
                        </div>
                      </div>
                      <div class="col-auto">
                        <div class="forum-stats">
                          <span><i class="far fa-eye"></i> 70</span>
                          <span><i class="far fa-comment"></i> 3</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
              <nav aria-label="Forum pagination">
                <ul class="pagination pagination-sm pagination-custom mb-0">
                  <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                  </li>
                  <li class="page-item"><a class="page-link" href="javascript:void(0)">1</a></li>
                  <li class="page-item active"><span class="page-link">2</span></li>
                  <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="javascript:void(0)"><i class="fas fa-chevron-right"></i></a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>

          <!-- Forum Detail (Hidden by default) -->
          <div class="forum-content" id="forumDetail" style="display: none;">
            <div class="mb-3">
              <button class="btn btn-outline-secondary btn-sm d-flex align-items-center" onclick="showForumList()">
                <i class="fas fa-arrow-left me-2"></i>Back to Forum
              </button>
            </div>

            <div class="card forum-card mb-3">
              <div class="card-body">
                <div class="row">
                  <div class="col-auto text-center">
                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle" width="60" alt="User" />
                    <small class="d-block text-muted mt-1">Newbie</small>
                  </div>
                  <div class="col">
                    <div class="d-flex align-items-center mb-2">
                      <h6 class="mb-0 me-2">Mokrani</h6>
                      <small class="text-muted">1 hour ago</small>
                    </div>
                    <h4 class="mb-3">Realtime fetching data</h4>
                    <div class="forum-description">
                      <p>Hellooo :)</p>
                      <p>I'm newbie with laravel and i want to fetch data from database in realtime for my dashboard anaytics and i found a solution with ajax but it dosen't work if any one have a simple solution it will be helpful</p>
                      <p>Thank</p>
                    </div>
                  </div>
                  <div class="col-auto">
                    <div class="forum-stats">
                      <span><i class="far fa-eye"></i> 19</span>
                      <span><i class="far fa-comment"></i> 3</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Reply -->
            <div class="card forum-card">
              <div class="card-body">
                <div class="row">
                  <div class="col-auto text-center">
                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="rounded-circle" width="50" alt="User" />
                    <small class="d-block text-muted mt-1">Pro</small>
                  </div>
                  <div class="col">
                    <div class="d-flex align-items-center mb-2">
                      <h6 class="mb-0 me-2">drewdan</h6>
                      <small class="text-muted">1 hour ago</small>
                    </div>
                    <div class="forum-description">
                      <p>What exactly doesn't work with your ajax calls?</p>
                      <p>Also, WebSockets are a great solution for realtime data on a dashboard. Laravel offers this out of the box using broadcasting</p>
                    </div>
                    <div class="mt-3">
                      <button class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-heart me-1"></i>1
                      </button>
                      <button class="btn btn-sm btn-outline-primary">Reply</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- New Thread Modal - ตรงกลางหน้าจอ -->
  <div class="modal fade" id="threadModal" tabindex="-1" aria-labelledby="threadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <form>
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="threadModalLabel">
              <i class="fas fa-plus me-2"></i>New Discussion
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="threadTitle" class="form-label">Title</label>
              <input type="text" class="form-control" id="threadTitle" placeholder="Enter title" autofocus />
            </div>
            <div class="mb-3">
              <label for="threadContent" class="form-label">Content</label>
              <textarea class="form-control" id="threadContent" rows="6" placeholder="Write your discussion content here..."></textarea>
            </div>
            <div class="mb-3" style="max-width: 300px;">
              <label for="customFile" class="form-label">Attachment</label>
              <input type="file" class="form-control form-control-sm" id="customFile" multiple />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">
              <i class="fas fa-paper-plane me-2"></i>Post
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php include '../../includes/footer.php' ?>

  <script src="../../assets/js/bootstrap.bundle.min.js"></script>

  <script>
    function showForumDetail() {
      document.getElementById('forumList').style.display = 'none';
      document.getElementById('forumDetail').style.display = 'block';
    }

    function showForumList() {
      document.getElementById('forumDetail').style.display = 'none';
      document.getElementById('forumList').style.display = 'block';
    }

    // Auto-collapse sidebar on mobile after clicking menu item
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarLinks = document.querySelectorAll('.sidebar-nav .nav-link');
      const sidebarCollapse = document.getElementById('sidebarNav');

      sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
          if (window.innerWidth < 992) {
            const bsCollapse = new bootstrap.Collapse(sidebarCollapse, {
              toggle: false
            });
            bsCollapse.hide();
          }
        });
      });
    });
  </script>
</body>

</html>