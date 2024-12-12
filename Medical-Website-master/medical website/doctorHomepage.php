<?php
// Mulai sesi atau lakukan pengaturan database jika diperlukan
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicare | Doctor Dashboard</title> 
    <!-- Page Icon -->
    <link rel="shortcut icon" href="image/heartbeat-solid.svg" type="image/x-icon">   
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <!-- Custom Css File Link -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bot.css">
</head>
<body>
    <!-- Header Section Starts -->
    <div class="header">
        <a href="#" class="logo"><i class="fas fa-heartbeat"></i> medicare.</a>
        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#services">services</a>
            <a href="#about">about</a>
            <a href="#dashboard">dashboard</a>
            <a href="#appointments">appointments</a>
            <a href="profile.php">Profile</a>
        </nav>
        <div id="menu-btn" class="fas fa-bars"></div>
    </div>
    <!-- Header Section End -->

    <!-- Home Section starts -->
    <section class="home" id="home">
        <div class="image">
            <img src="./image/home-img.svg" alt="home-img.svg">
        </div>
        <div class="content">
            <h3>
                <?php
                // Jika dokter telah login, tampilkan nama mereka
                echo isset($_SESSION['doctor_name']) ? "Welcome, Dr. " . htmlspecialchars($_SESSION['doctor_name']) : "Welcome, Doctor";
                ?>
            </h3>
            <p>Manage your appointments, patients, and medical records easily with our intuitive doctor dashboard. Stay updated on the latest healthcare trends and insights.</p>
        </div>
    </section>
    <!-- Home Section End -->

    <!-- Icons section starts  -->
    <section class="icons-container">
        <div class="icons">
            <i class="fas fa-user-md"></i>
            <h3>140+</h3>
            <p>Experienced doctors</p>
        </div>
        <div class="icons">
            <i class="fas fa-users"></i>
            <h3>1040+</h3>
            <p>Satisfied patients</p>
        </div>
        <div class="icons">
            <i class="fas fa-hospital-user"></i>
            <h3>500+</h3>
            <p>Successful treatments</p>
        </div>
        <div class="icons">
            <i class="fas fa-stethoscope"></i>
            <h3>80+</h3>
            <p>Partner hospitals</p>
        </div>
    </section>
    <!-- Icons section End  -->

    <!-- Dashboard section Starts -->
    <section class="dashboard" id="dashboard">
        <h1 class="heading">Doctor <span>Dashboard</span></h1>
        <div class="box-container">
            <div class="box">
                <i class="fas fa-calendar-check"></i>
                <h3>Manage Appointments</h3>
                <p>View and manage your daily schedule with ease. Accept or reschedule appointments based on your availability.</p>
                <a href="#appointments" class="btn">Manage Appointments <span class="fas fa-chevron-right"></span> </a>
            </div>
            <div class="box">
                <i class="fas fa-file-medical-alt"></i>
                <h3>Medical Records</h3>
                <p>Access your patients' medical records securely. Review and update reports in real-time for better diagnosis.</p>
                <a href="viewRecord.php" class="btn">View Records <span class="fas fa-chevron-right"></span> </a>
            </div>
            <div class="box">
                <i class="fas fa-users"></i>
                <h3>Patient Management</h3>
                <p>Keep track of your patients' health history and treatment plans. Monitor their progress over time.</p>
                <a href="managePatient.php" class="btn">Manage Patients <span class="fas fa-chevron-right"></span> </a>
            </div>
            <div class="box">
                <i class="fas fa-chart-line"></i>
                <h3>Health Insights</h3>
                <p>Get insights on recent healthcare trends and analytics to stay informed and enhance your practice.</p>
                <a href="HealthInsights.php" class="btn">View Insights <span class="fas fa-chevron-right"></span> </a>
            </div>
        </div>
    </section>
    <!-- Dashboard section End -->

     <!-- Footer section starts -->
     <section class="footer">
            <div class="box-container">
                <div class="box">
                    <h3>Quick Links</h3>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Home</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Services</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> About</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Doctors</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Book</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Review</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Blogs</a>
                </div>
                <div class="box">
                    <h3>Our Services</h3>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Free Checkups</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> 24/7 Ambulance</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Expert Doctors</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Pharmacy Services</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> In-Patient Care</a>
                </div>
                <div class="box">
                    <h3>Contact Info</h3>
                    <a href="#"> <i class="fas fa-phone"></i> +123-456-7890</a>
                    <a href="#"> <i class="fas fa-phone"></i> +111-222-3333</a>
                    <a href="#"> <i class="fas fa-envelope"></i> info@medcare.com</a>
                    <a href="#"> <i class="fas fa-map-marker-alt"></i> Action Area 1, Newtown, India </a>
                </div>
            </div>
            <div class="credit"> © 2024 Medcare. All Rights Reserved.</div>
        </section>

    <!-- Chatbot Section -->
    <div class="chatbot-toggler" id="chatbotToggler">
        <span>🤖</span>
    </div>
    <div class="chatbot" id="chatbot">
        <header>
            <h2>Chatbot</h2>
        </header>
        <div class="chatbox">
            <ul class="chat"></ul>
        </div>
        <div class="chat-input">
            <textarea placeholder="Type a message..."></textarea>
            <span>&#10148;</span>
        </div>
    </div>

    <script>
        const chatbotToggler = document.getElementById('chatbotToggler');
        const chatbot = document.getElementById('chatbot');
        const body = document.body;

        chatbotToggler.addEventListener('click', () => {
            body.classList.toggle('show-chatbot');
        });
    </script>
</body>
</html>
