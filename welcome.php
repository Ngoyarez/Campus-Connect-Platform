<?php
// Initialize the session
session_start();

include 'config.php';
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Fetch posts from the database
$sql = "SELECT posts.id, posts.title, posts.content, posts.post_image, posts.likes, posts.user_id, users.username FROM posts JOIN users ON posts.user_id = users.id";
$result = mysqli_query($conn, $sql);

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="./assets/jquery/jquery.min.js"></script>
</head>
    <style>
        body{ 
            font: 14px sans-serif; 
            text-align: center; 
        }
        .navbar-nav .nav-item .nav-link img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
        .navbar-nav .nav-item .nav-link {
            display: flex;
            align-items: center;
        }
        .col-center {
            margin: 0 auto;
            float: none !important;
        }
        .carousel {
            margin: 50px auto;
            padding: 0 70px;
        }
        .carousel-item {
            color: #999;
            font-size: 14px;
            text-align: center;
            overflow: hidden;
            min-height: 290px;
        }
        .carousel .item .img-box {
            width: 135px;
            height: 135px;
            margin: 0 auto;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 50%;
        }
        .carousel .img-box img {
            width: 100%;
            height: 100%;
            display: block;
            border-radius: 50%;
        }
        .carousel .testimonial {
            padding: 30px 0 10px;
        }
        .carousel .overview {   
            font-style: italic;
        }
        .carousel .overview b {
            text-transform: uppercase;
            color: #db584e;
        }
        .carousel .carousel-control {
            width: 40px;
            height: 40px;
            margin-top: -20px;
            top: 50%;
            background: none;
        }
        .carousel-control i {
            font-size: 68px;
            line-height: 42px;
            position: absolute;
            display: inline-block;
            color: rgba(0, 0, 0, 0.8);
            text-shadow: 0 3px 3px #e6e6e6, 0 0 0 #000;
        }
        .carousel .carousel-indicators {
            bottom: -40px;
        }
        .carousel-indicators li, .carousel-indicators li.active {
            width: 10px;
            height: 10px;
            margin: 1px 3px;
            border-radius: 50%;
        }
        .carousel-indicators li {   
            background: #999;
            border-color: transparent;
            box-shadow: inset 0 2px 1px rgba(0,0,0,0.2);
        }
        .carousel-indicators li.active {    
            background: #555;       
            box-shadow: inset 0 2px 1px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#"><img src="./images/story-logo.png" alt="Logo" style="width: 150px; height: 100px;" class="text-start"></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav p-3">
                <a class="nav-item nav-link active" href="#" style="font-size: 25px;">Home</a>
                <a class="nav-item nav-link" href="about.php" style="font-size: 25px; display: flex; align-items: center;">About</a>

                <a class="nav-item nav-link" href="about.php" style="font-size: 25px; display: flex; align-items: center;">
                    <div class="dropdown">
                      <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 25px;">
                        Posts
                      </a>

                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_post.php">Add Post</a>
                        <a class="dropdown-item" href="posts_display.php">View Posts</a>
                      </div>
                    </div>
                </a>

                <a class="nav-item nav-link" href="contact.php" style="font-size: 25px; display: flex; align-items: center;">Contact Us</a>
            </div>

            <form class="form-inline mx-auto" action="search.php" method="get" style="display: flex; align-items: center;">
                <input class="form-control mr-sm-2" type="search" placeholder="Search posts" aria-label="Search" name="query" style="width: 300px; font-size: 20px;">
                <select class="form-control mr-sm-2" name="filter" style="font-size: 20px;">
                    <option value="title">Title</option>
                    <option value="user">User</option>
                    <option value="time">Time</option>
                    <option value="school">School</option>
                    <option value="category">Category</option>
                </select>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit" style="font-size: 20px;">Search</button>
            </form>

            <div class="navbar-nav ms-auto p-3">
                <a class="nav-item nav-link" href="#" style="font-size: 25px;">
                    <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture" style="border-radius: 50%; width: 50px; height: 30px; margin-right: 15px">
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <a class="nav-item nav-link" href="./logout.php" style="font-size: 25px;">Logout</a>
            </div>
        </div>
    </nav>

        <!-- <div class="container w-50 text-start justify-content-center" style="font-size: 25px;">
            <h1>Who are We</h1>
            <p>Welcome to our campus community platform! This platform aims to bring students together, allowing them to share ideas, ask questions, and collaborate on various academic and social activities.</p>
            <p>Our platform offers features such as posting articles, commenting, liking, disliking, and replying to comments, ensuring a vibrant and interactive community experience.</p>
        </div> -->

        <div class="container">
            <div class="row mt-5">
                <div class="col-md-6 text-start">
                    <h1>Connect with Like Minded Campus Students</h1>
                    <p style="font-size: 18px;">Welcome to our campus community platform! This platform aims to bring students together, allowing them to share ideas, ask questions, and collaborate on various academic and social activities.</p>
                    <p style="font-size: 18px;">Our platform offers features such as posting articles, commenting, liking, disliking, and replying to comments, ensuring a vibrant and interactive community experience.</p>
                </div>

                <div class="col-md-6">
                    <img src="./images/college.jpg" width="100%" height="auto" alt="Campus Connect Image">
                </div>
            </div>

            <!-- Campus Experience Section -->
            <div class="row mt-5 ptb-100">
                <h1 class="text-start" style="font-size: 25px;">Unlock Your Campus Experience</h1>
                <div class="col-md-6">
                    <img src="./images/campus_experience.jpg" alt="Campus Experience Image" width="100%" height="auto" class="img-fluid">
                </div>

                <div class="col-md-6">
                    <p>Join our campus community platform and enjoy a wide range of benefits:</p>
                    <ul class="list-group list-group-flush text-start" style="font-size: 18px;">
                        <li class="list-group-item">Connect with fellow students from your campus</li>
                        <li class="list-group-item">Discover new academic and social opportunities</li>
                        <li class="list-group-item">Stay informed about campus events and activities</li>
                        <li class="list-group-item">Share your knowledge and experiences with others</li>
                        <li class="list-group-item">Collaborate on projects and group assignments</li>
                        <li class="list-group-item">Get support and advice from peers and mentors</li>
                        <li class="list-group-item">Explore diverse perspectives and ideas</li>
                        <li class="list-group-item">Build lasting friendships and networks</li>
                    </ul>
                </div>
            </div>
        
            <!-- Recent Posts Section -->
            <div class="row mt-5 ptb-100 gap-3">
                <h2 class="text-start">Recent Posts</h2>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card col-md-4" style="width: 18rem;">
                        <img class="card-img-top" src="<?php echo $row['post_image']; ?>" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text">
                                <?php 
                                    $content = htmlspecialchars($row['content']); 
                                    echo strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content;
                                ?>
                            </p>
                            <span class="text-muted">Posted by <?php echo htmlspecialchars($row['username']); ?></span>
                            <p class="card-text">Likes: <?php echo htmlspecialchars($row['likes']); ?></p>
                            <a href="post_details.php?post_id=<?php echo $row['id']; ?>" class="btn btn-primary">Read More</a>
                            <a href="like.php?post_id=<?php echo $row['id']; ?>" class="btn btn-success">Like</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Testimonials Section -->
            <div class="row mt-5 ptb-100 gap-3">
                <div class="col-md-12 m-auto">
                    <h2 class="text-start">Testimonials</h2>
                    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
                        <!-- Carousel -->
                        <div class="carousel-inner">
                            <div class="item carousel-item active">
                                <div class="img-box"><img src="https://i.ibb.co/d5DY64w/img1.jpg" alt=""></div>
                                <p class="testimonial">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam eu sem tempor, varius quam at, luctus dui. Mauris magna metus, dapibus nec turpis vel, semper malesuada ante. Idac bibendum scelerisque non non purus. Suspendisse varius nibh non aliquet.</p>
                                <p class="overview"><b>Jennifer Smith</b>, Office Worker</p>
                            </div>
                            <div class="item carousel-item">
                                <div class="img-box"><img src="https://i.ibb.co/5FF1vqz/img2.jpg" alt=""></div>
                                <p class="testimonial">Vestibulum quis quam ut magna consequat faucibus. Pellentesque eget nisi a mi suscipit tincidunt. Utmtc tempus dictum risus. Pellentesque viverra sagittis quam at mattis. Suspendisse potenti. Aliquam sit amet gravida nibh, facilisis gravida odio.</p>
                                <p class="overview"><b>Dauglas McNun</b>, Financial Advisor</p>
                            </div>
                            <div class="item carousel-item">
                                <div class="img-box"><img src="https://i.ibb.co/Trv7hDv/img3.jpg" alt=""></div>
                                <p class="testimonial">Phasellus vitae suscipit justo. Mauris pharetra feugiat ante id lacinia. Etiam faucibus mauris id tempor egestas. Duis luctus turpis at accumsan tincidunt. Phasellus risus risus, volutpat vel tellus ac, tincidunt fringilla massa. Etiam hendrerit dolor eget rutrum.</p>
                                <p class="overview"><b>Hellen Wright</b>, Athelete</p>
                            </div>
                        </div>
                        <!-- Carousel Controls -->
                        <a class="carousel-control left carousel-control-prev" href="#myCarousel" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a class="carousel-control right carousel-control-next" href="#myCarousel" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>


            <!-- FAQs Section -->
            <div class="row mt-5 ptb-100 gap-3">
                <div class="col-md-12">
                    <h2 class="text-start" style="font-size: 25px;">Frequently Asked Questions (FAQs)</h2>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1" style="font-size: 18px;">
                                    Question 1: What is the purpose of this platform?
                                </button>
                            </h2>
                            <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="font-size: 18px;">
                                    Answer: Our platform aims to bring students together, allowing them to share ideas, ask questions, and collaborate on various academic and social activities.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2" style="font-size: 18px;">
                                    Question 2: How do I post articles or questions?
                                </button>
                            </h2>
                            <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="font-size: 18px;">
                                    Answer: To post articles or questions, you need to create an account and log in. Once logged in, you can navigate to the relevant section and use the provided form to submit your content.
                                </div>
                            </div>
                        </div>
                        <!-- Add more FAQs as needed -->
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Optional: Add an image or other content -->
                </div>
            </div>
        </div>


        <!-- Footer Section -->
        <?php include './footer.php'; ?>
         <script>
            // Activate the carousel
            $(document).ready(function(){
                $('#myCarousel').carousel();
            });
        </script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>