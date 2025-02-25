<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Water Web" content="A brief description of the page for SEO">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="./css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    
</head>

<header class="main-header">
    <nav class="main-nav">
      <div class="main-cont">
        <div class="user-account">
            <div class="user-profile">
                <img src="./css/images/water.png" alt="Profile Image">
            </div>
            <div class="user-detail">
              <h3>Admin</h3>
              <span>Admin</span>
            </div>
        </div>
            <div class="btn" id="btn">
            <span class="material-symbols-outlined" id="arrow">keyboard_arrow_down</span>
            </div>
        </div>
        <div class="dropdown" id="dropdown">
            <ul class="menu-bar">
                <!-- Main menu items -->
                <li class="setting-item"><a href="#"><div class="icon"><span class="fas fa-cog"></span></div>Settings & privacy <i class="fas fa-angle-right"></i></a></li>
                <li class="help-item"><a href="#"><div class="icon"><span class="fas fa-question-circle"></span></div>Help & support <i class="fas fa-angle-right"></i></a></li>
                <li><a href="#"><div class="icon"><span class="fas fa-comment-alt"></span></div>Feedback</a></li>
                <li><a href="#"><div class="icon"><span class="fa-solid fa-bell"></span></div>Notifications</a></li>
                <li><a href="database/logout.php"><div class="icon"><span class="fas fa-sign-out-alt"></span></div>Log out</a></li>
            </ul>
            <!-- Settings & privacy Menu-items -->
            <ul class="setting-drop">
                <li class="arrow back-setting-btn"><span class="fas fa-arrow-left"></span>Settings & privacy</li>
                <li><a href="#"><div class="icon"><span class="fas fa-user"></span></div>Personal info</a></li>
                <li><a href="#"><div class="icon"><span class="fas fa-lock"></span></div>Password</a></li>
                <li><a href="#"><div class="icon"><span class="fas fa-address-book"></span></div>Activity log</a></li>
                <li><a href="#"><div class="icon"><span class="fas fa-globe-asia"></span></div>Languages</a></li>
            </ul>
            <!-- Help & support Menu-items -->
            <ul class="help-drop">
                <li class="arrow back-help-btn"><span class="fas fa-arrow-left"></span>Help & support</li>
                <li><a href="#"><div class="icon"><span class="fas fa-question-circle"></span></div>Help centre</a></li>
                <li><a href="#"><div class="icon"><span class="fas fa-envelope"></span></div>Support Inbox</a></li>
                <li><a href="#"><div class="icon"><span class="fas fa-comment-alt"></span></div>Send feedback</a></li>
                <li><a href="#"><div class="icon"><span class="fas fa-exclamation-circle"></span></div>Report problem</a></li>
            </ul>
        </div>
    </nav>
</header>

<body>
  <aside class="sidebar">
    <div class="sidebar-header" id="dropdown">
      <img src="./css/images/water.png" alt="logo" />
      <h2>Rain Water</h2>
    </div>
    <ul class="sidebar-links">
      <h4>
        <span>Main Menu</span>
        <div class="menu-separator"></div>
      </h4>
      <li>
        <a href="main.php">
          <span class="material-symbols-outlined"> dashboard </span>Dashboard</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> overview </span>Overview</a>
      </li>
      <li>
        <a href="analytics.php"><span class="material-symbols-outlined"> monitoring </span>Analytic</a>
      </li>
      <h4>
        <span>General</span>
        <div class="menu-separator"></div>
      </h4>
      <li>
        <a href="user-lists.php"><span class="material-symbols-outlined"> groups </span>User Lists</a>
      </li>
      <li>
      <div class="dropdown-btn">
        <a href="#"><span class="material-symbols-outlined">patient_list</span>Customer<i class="fa fa-caret-down"></i></a>
      </div>
        <div class="dropdown-container">
          <a href="add-customer.php"><span class="material-symbols-outlined"> person_add </span>Add Customer</a>
          <a href="customer-lists.php"><span class="material-symbols-outlined"> lists </span>Customer Lists</a>
        </div>
      </li>
      <li>
      <div class="dropdown-btn">
        <a href="#"><span class="material-symbols-outlined"> local_shipping </span>Orders <i class="fa fa-caret-down"></i></a>
      </div>
        <div class="dropdown-container">
          <a href="add-order.php"><span class="material-symbols-outlined"> add_shopping_cart </span>Add Orders</a>
          <a href="order-lists.php"><span class="material-symbols-outlined"> list </span>Order List</a>
        </div>
      </li>
      <li>
      <div class="dropdown-btn">
        <a href="#"><span class="material-symbols-outlined"> flag </span>Admin<i class="fa fa-caret-down"></i></a>
      </div>
      <div class="dropdown-container">
          <a href="add-user.php"><span class="material-symbols-outlined"> person_add </span>Add User</a>
          <a href="view-user.php"><span class="material-symbols-outlined"> person_remove </span>Edit User</a>
          <a href="add-role.php"><span class="material-symbols-outlined"> group_add </span>Add Role</a>
          <a href="view-role.php"><span class="material-symbols-outlined"> group_remove </span>Edit Role</a>
          <a href="view-customer.php"><span class="material-symbols-outlined"> diversity_3 </span>Edit Customer</a>
          <a href="view-order.php"><span class="material-symbols-outlined"> remove_shopping_cart </span>Edit Orders</a>
        </div>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> calendar_month </span>Schedule</a>
      </li>
    </ul>
      
      
  </aside>
    <script src="./js/nav.js"></script>

</body>
</html>