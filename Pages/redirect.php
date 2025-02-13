<?
//if the page value is empty or the user is not logged in send the user back to the login page
 if (!str_ends_with($_SERVER['SCRIPT_NAME'], 'index.php')|| !isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    unset($_SESSION['password']);
    unset($_SESSION['name']);
    header('location: /~pmartus/CPS276/10/index.php');
}
?>