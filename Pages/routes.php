<?php
//* Final Homework - Routing
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//sets default page to login
$path = "index.php?page=login";
//setup the nav bar so that the admin options dont appear if the user is only staff
if (!empty($_SESSION['access'])) {
    $nav = <<<HTML
        <a href='index.php?page=addContact'>Add Contact</a>&nbsp;&nbsp;&nbsp;
        <a href='index.php?page=deleteContact'>Delete Contact</a>&nbsp;&nbsp;&nbsp;
        <a href='index.php?page=logOut'>Log Out</a>
    </nav>
HTML;
    if ($_SESSION['access'] == "Admin") {
        $nav = <<<HTML
        <nav>
            <a href='index.php?page=addAdmin'>Add Admin</a>&nbsp;&nbsp;&nbsp;
            <a href='index.php?page=deleteAdmin'>Delete Admin</a>&nbsp;&nbsp;&nbsp; 
    HTML . $nav;
    } else {
        $nav = <<<HTML
        <nav>
        HTML . $nav;
    }
} else {
    $nav = '';
}

//these statements run the buttons that navigate to the different pages
//(based on the Scott Shaper architecture)
if (isset($_GET)) {
    if ($_GET['page'] === "addContact") {
        require_once('Pages/addContact.php');
        $result = init();
    } elseif ($_GET['page'] === "deleteContact") {
        require_once('Pages/deleteContact.php');
        $result = init();
    } elseif ($_GET['page'] === "addAdmin") {
        require_once('Pages/addAdmin.php');
        $result = init();
    } elseif ($_GET['page'] === "deleteAdmin") {
        require_once('Pages/deleteAdmin.php');
        $result = init();
    } elseif ($_GET['page'] === "welcome") {
        require_once('Pages/welcome.php');
        $result = init();
    } elseif ($_GET['page'] === "login") {
        require_once('Pages/login.php');
        $nav = '';
        $result = init();
    } elseif($_GET['page'] === "logout") {
        header(('location: /~pmartus/CPS276/10/logout.php'));
        //if no location is specified we return to the login page
    } else {
        header('location: ' . $path);
    }
} else {
    header('location: ' . $path);
}
