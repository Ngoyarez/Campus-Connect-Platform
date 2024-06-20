<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <style>
        nav{
            width: 1200px;
            height: 150px;
            background-image: url('./images/story-logo.png');
            background-repeat: no-repeat;
            background-size: cover;
/*            opacity: 0.7;*/
        }
        .about-section {
            padding: 60px 0;
        }
        .about-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .about-section p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .team-section {
            padding: 60px 0;
            background-color: #f9f9f9;
        }
        .team-section .card {
            border: none;
            margin-bottom: 30px;
        }
        .team-section .card img {
            border-radius: 50%;
        }
        .team-section .card-title {
            margin-top: 15px;
            font-size: 1.5rem;
        }
        .tech-section {
            padding: 60px 0;
        }
        .tech-section ul {
            list-style: none;
            padding: 0;
        }
        .tech-section ul li {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100" aria-label="breadcrumb">
      <ol class="breadcrumb ml-5" style="font-size: 25px;">
        <li class="breadcrumb-item"><a href="welcome.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">About</li>
        <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
      </ol>
    </nav>

    <div class="container about-section">
        <h1>About Our Project</h1>
        <p>Welcome to our campus community platform! This project aims to bring students together, allowing them to share ideas, ask questions, and collaborate on various academic and social activities.</p>
        <p>Our platform offers features such as posting articles, commenting, liking, disliking, and replying to comments, ensuring a vibrant and interactive community experience.</p>
    </div>

    <div class="container team-section">
        <h2>Meet the Team</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="./images/Profile.png" class="card-img-top mx-auto" alt="Team Member 1" style="width: 50%;">
                    <div class="card-body">
                        <h5 class="card-title">Brian Ngoya</h5>
                        <p class="card-text">Project Manager</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="./images/Profile.png" class="card-img-top mx-auto" alt="Team Member 2" style="width: 50%;">
                    <div class="card-body">
                        <h5 class="card-title">Traversy Media</h5>
                        <p class="card-text">Lead Developer</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="./images/Profile.png" class="card-img-top mx-auto" alt="Team Member 3" style="width: 50%;">
                    <div class="card-body">
                        <h5 class="card-title">John Smith</h5>
                        <p class="card-text">UI/UX Designer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="container tech-section">
        <h2>Technologies Used</h2>
        <ul>
            <li>HTML5 & CSS3</li>
            <li>HTML5 & CSS3</li>
            <li>HTML5 & CSS3</li>
        </ul>
    </div> -->
</body>
</html>
