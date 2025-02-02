<?php
include '../autoload.php';
session_start();

use BusinessLayer\clsCatagory;
use BusinessLayer\clsMovie;
use BusinessLayer\clsUser;
$Crruntuser = new clsUser();
if (isset($_SESSION['username']) && $_SESSION['password']) {
    $Crruntuser = clsUser::FindByNameAndPassword($_SESSION['username'], $_SESSION['password']);
    if ($Crruntuser->role != 1) {
        header('Location: ../sign in .php?logout=true');
        exit();
    }
}
else if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
    header('Location: ../sign in .php?logout=true');
    exit();
}


$allCatagorise = clsCatagory::getAllCategories();
$allMovies = clsMovie::getAllMovies();
$allUsers;
clsUser::GetAllUsers($allUsers);


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Control Panel</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="../../../images/icon.jpg" sizes="16x16" type="image/jpg">
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top" role="navigation" style="margin-bottom: 0px">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" style="background-color: #e5b68a;" href="index.php">Control panel</a>
            </div>
            <!-- <label style="margin-right:40px;color:white;margin-left:800px;margin-top:18px;">Welcome User</label> -->
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px; display: flex;">
                <a href="../index.php" style="background-color: #e5b68a;"
                    class="btn btn-success square-btn-adjust">Logout</a>
            </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu" style="background-color: #f6f6f6;">
                    <li>
                        <a href="index.php"><i class="fa fa-dashboard"></i> Control panel</a>
                    </li>
                    <li>
                        <a href="users.php"><i class="fa fa-users"></i> Users</a>
                    </li>

                    <li>
                        <a href="Movies.php"><i class="fa fa-bars"></i> Movies</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2><i class="fa fa-dashboard"></i> Control panel</h2>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />
                <div class="row">
                    <!-- Users Panel -->
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-red set-icon">
                                <i class="fa fa-users"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo count($allUsers) . " User" ?></p>
                                <br>
                                <br>
                            </div>
                            <a href="users.php">
                                <div class="panel-footer" style="color: red;">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- Categories Panel -->
                    <!-- <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-green set-icon">
                                <i class="fa fa-tasks"></i>
                            </span> -->
                    <!-- <div class="text-box">
                                <p class="main-text">5 Categories</p>
                                <br>
                                <br>
                            </div> -->
                    <!-- <a href="categories.php">
                                <div class="panel-footer" style="color: #00ce6f;">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a> -->
                    <!-- </div>
            </div> -->
                    <!-- Products Panel -->
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-blue set-icon">
                                <i class="fa fa-bars"></i>
                            </span>
                            <div class="text-box">
                                <p class="main-text"><?php echo count($allMovies) . " Movie" ?></p>
                                <br>
                                <br>
                            </div>
                            <a href="Movies.php">
                                <div class="panel-footer" style="color: #a95df0;">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /. PAGE INNER  -->
            </div>
            <!-- /. PAGE WRAPPER  -->
        </div>
        <!-- FOOTER -->
        <footer>
            <div class="container">
                <p class="text-muted">© 2023 Control Panel. All Rights Reserved.</p>
            </div>
        </footer>
        <!-- /. FOOTER -->
    </div>
    <!-- SCRIPTS - AT THE BOTTOM TO REDUCE THE LOAD TIME -->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- MORRIS CHART SCRIPTS -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>