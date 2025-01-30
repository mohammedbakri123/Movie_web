<?php
include 'autoload.php';
use BusinessLayer\clsUser;
//session_start();
$user = clsUser::FindByNameAndPassword($_SESSION['username'], $_SESSION['password']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies Land</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- icons link start -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- icons link end -->
    <!-- start swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


</head>

<body>
    <!-- Header -->

    <header>
        <a href="index.php" class="logo">
            <i class="bx bxs-movie"></i>Movies
        </a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <!-- Menu -->
        <ul class="navbar">
            <li> <a href="#home" class="home-active">Home</a></li>
            <li> <a href="#Movies">Movies</a></li>
            <li> <a href="#coming">Coming</a></li>
            <li> <a href="search.php">Search</a></li>
            <?php if ($user->role == 1) { ?>
                <li> <a href="admin/index.php">Admin</a></li>
            <?php } ?>
        </ul>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <a href="sign in .php?logout=true" class="btn" name="btnLogout">Logout</a>
        </form>

    </header>