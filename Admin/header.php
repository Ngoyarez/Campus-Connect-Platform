<?php
include '../config.php'; 
$sql = "SELECT * FROM admin";
$result= mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div class="container-fluid w-100">
  <nav class="navbar navbar-expand-lg bg-body-tertiary bsb-navbar-3 w-100">
    <div class="container">
      <ul class="navbar-nav">
        <li class="nav-item me-3">
          <a class="nav-link" href="#!" data-bs-toggle="offcanvas" data-bs-target="#bsbSidebar1" aria-controls="bsbSidebar1">
            <!-- <i class="fas fa-filter fs-3 lh-1"></i> -->
          </a>
        </li>
      </ul>
      <a class="navbar-brand" href="#!">
        <img src="../images/story-logo.png" class="img-fluid" alt="Logo" width="135" height="25">
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#bsbNavbar" aria-controls="bsbNavbar" aria-label="Toggle Navigation">
        <i class="bi bi-three-dots"></i>
      </button>
      <div class="collapse navbar-collapse" id="bsbNavbar">
        <ul class="navbar-nav bsb-dropdown-menu-responsive ms-auto align-items-center">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle bsb-dropdown-toggle-caret-disable" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="position-relative pt-1">
                <i class="fas fa-search"></i>
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-md-end bsb-dropdown-animation bsb-fadeIn">
              <form class="row g-1 px-3 py-2 align-items-center">
                <div class="col-8">
                  <label class="visually-hidden" for="inputSearchNavbar">Search</label>
                  <input type="text" class="form-control" id="inputSearchNavbar">
                </div>
                <div class="col-4">
                  <button type="submit" class="btn btn-primary">Search</button>
                </div>
              </form>
            </div>
          </li>
    
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle bsb-dropdown-toggle-caret-disable" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="position-relative pt-1">
                <i class="fas fa-comment"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                  9
                  <span class="visually-hidden">New Chats</span>
                </span>
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-md-end bsb-dropdown-animation bsb-fadeIn">
              <div>
                <h6 class="dropdown-header fs-7 text-center">9 New Messages</h6>
              </div>
              <div>
                <hr class="dropdown-divider mb-0">
              </div>
              <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                  <div class="row g-0 align-items-center">
                    <div class="col-2">
                      <img src="<?php echo $row['profile_picture']; ?>" class="img-fluid rounded-circle" alt="Luna John">
                    </div>
                    <div class="col-10">
                      <div class="ps-3">
                        <div class="text-dark">Luna John</div>
                        <div class="text-secondary mt-1 fs-7">Hello, I'm having trouble with my account.</div>
                        <div class="text-secondary mt-1 fs-7">15m ago</div>
                      </div>
                    </div>
                  </div>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                  <div class="row g-0 align-items-center">
                    <div class="col-2">
                      <img src="./assets/img/chat/chat-img-2.jpg" class="img-fluid rounded-circle" alt="Mark Smith">
                    </div>
                    <div class="col-10">
                      <div class="ps-3">
                        <div class="text-dark">Mark Smith</div>
                        <div class="text-secondary mt-1 fs-7">Hi, I'm not able to change my password.</div>
                        <div class="text-secondary mt-1 fs-7">23m ago</div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <div>
                <hr class="dropdown-divider mt-0">
              </div>
              <div>
                <a class="dropdown-item fs-7 text-center" href="#">See All Messages</a>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle bsb-dropdown-toggle-caret-disable" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="position-relative pt-1">
                <i class="fas fa-bell"></i>
                <span class="p-1 bg-danger border border-light rounded-circle position-absolute top-0 start-100 translate-middle">
                  <span class="visually-hidden">New Notifications</span>
                </span>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-md-end bsb-dropdown-animation bsb-fadeIn">
              <li>
                <h6 class="dropdown-header fs-7 text-center">18 Notifications</h6>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="#!">
                  <span>
                    <i class="fas fa-envelope"></i>
                    <span class="fs-7">New Messages</span>
                  </span>
                  <span class="fs-7 ms-auto text-secondary">5 mins</span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="#!">
                  <span>
                    <i class="fas fa-user-friends"></i>
                    <span class="fs-7">Friend Requests</span>
                  </span>
                  <span class="fs-7 ms-auto text-secondary">17 hours</span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="#!">
                  <span>
                    <i class="fas fa-file-alt"></i>
                    <span class="fs-7">New Reports</span>
                  </span>
                  <span class="fs-7 ms-auto text-secondary">3 days</span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item fs-7 text-center" href="#">See All Notifications</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle bsb-dropdown-toggle-caret-disable" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- <i class="fas fa-user-circle"></i> -->
              <img src="<?php echo $row['profile_picture']; ?>" width="35" height="35" class="img-fluid rounded-circle" alt="L">
            </a>
            <ul class="dropdown-menu dropdown-menu-md-end bsb-dropdown-animation bsb-fadeIn">
              <li>
                <h6 class="dropdown-header fs-7 text-center">Welcome, <?php echo $row['username']; ?></h6>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a href="#" class="dropdown-item" aria-current="true">
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#!">
                  <span>
                    <i class="bi bi-person-fill me-2"></i>
                    <span class="fs-7">View Profile</span>
                  </span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#!">
                  <span>
                    <i class="bi bi-bell-fill me-2"></i>
                    <span class="fs-7">Notifications</span>
                  </span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" href="#!">
                  <span>
                    <i class="bi bi-gear-fill me-2"></i>
                    <span class="fs-7">Settings & Privacy</span>
                  </span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#!">
                  <span>
                    <i class="bi bi-question-circle-fill me-2"></i>
                    <span class="fs-7">Help Center</span>
                  </span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item text-center" href="#!">
                  <span>
                    <span class="fs-7">Log Out</span>
                  </span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>