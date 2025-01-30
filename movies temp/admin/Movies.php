<?php
include '../autoload.php';
use BusinessLayer\clsCatagory;
use BusinessLayer\clsMovie;

function encryptParam($value)
{
    $key = 'your-256-bit-secret'; // Replace with a secure key
    $cipher = 'aes-256-cbc';
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($value, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptParam($value)
{
    $key = 'your-256-bit-secret';
    $cipher = 'aes-256-cbc';
    $decoded = base64_decode($value);
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($decoded, 0, $ivlen);
    $encrypted = substr($decoded, $ivlen);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

// Decrypt GET parameters

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
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px; display: flex;">
                <a href="../index.php" style="background-color: #e5b68a;"
                    class="btn btn-success square-btn-adjust">Logout</a>
            </div>
        </nav>
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
                        <a href="categories.php"><i class="fa fa-tasks"></i> Categories</a>
                    </li>
                    <li>
                        <a href="Movies.php"><i class="fa fa-bars"></i> Movies</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2><i class="fa fa-bars"></i> Movie</h2>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-plus-circle"></i> Add New Movie
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        $action = isset($_GET['action']) ? $_GET['action'] : '';
                                        $encryptedMovieID = isset($_GET['MovieID']) ? $_GET['MovieID'] : '';
                                        $MovieID = $encryptedMovieID ? decryptParam($encryptedMovieID) : '';

                                        // Handle GET actions (Edit/Delete)
                                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                                            switch ($action) {
                                                case 'edit':
                                                    $movie = clsMovie::getMovieById($MovieID);
                                                    break;
                                                case 'delete':
                                                    if (clsMovie::deleteMovie($MovieID)) {
                                                        header("Location: Movies.php");
                                                        exit;
                                                    }
                                                    break;
                                            }
                                        }

                                        // Handle POST submissions (Add/Edit)
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            $name = $_POST['Name'];
                                            $length = $_POST['Length'];
                                            $year = $_POST['Year'];
                                            $category = $_POST['Catagory'];
                                            $status = $_POST['MovieStatus'];
                                            $movieID = isset($_POST['MovieID']) ? $_POST['MovieID'] : '';

                                            if (isset($_POST['BtnAddMovie'])) {
                                                $newMovie = new clsMovie();
                                                $newMovie->name = $name;
                                                $newMovie->movieLength = $length;
                                                $newMovie->publishYear = $year;
                                                $newMovie->Catagory = $category;
                                                $newMovie->movieStatus = $status;
                                                // Handle file uploads here
                                                if ($newMovie->Save()) {
                                                    header("Location: Movies.php");
                                                    exit;
                                                }
                                            } elseif (isset($_POST['BtnEditMovie'])) {
                                                $existingMovie = clsMovie::getMovieById($movieID);
                                                $existingMovie->name = $name;
                                                $existingMovie->movieLength = $length;
                                                $existingMovie->publishYear = $year;
                                                $existingMovie->Catagory = $category;
                                                $existingMovie->movieStatus = $status;
                                                // Handle file updates here
                                                if ($existingMovie->Save()) {
                                                    header("Location: Movies.php");
                                                    exit;
                                                }
                                            }
                                        }


                                        ?>
                                        <form action="Movies.php" role="form" enctype="multipart/form-data"
                                            method="POST">
                                            <div class="form-group">
                                                <label>Movie Name</label>
                                                <input type="text" placeholder="Please Enter Movie Name "
                                                    class="form-control" name="Name">
                                            </div>
                                            <div class="form-group">
                                                <label>Length in Mins</label>
                                                <input type="text" placeholder="Please Enter a Number"
                                                    class="form-control" name="Length">
                                            </div>
                                            <div class="form-group">
                                                <label>The Movie</label><br>
                                                <input type="file" class="form-control" name="MovieFile">
                                                <label>Movie Poster</label><br>
                                                <input type="file" class="form-control" name="Poster">
                                                <label>Picture 1 </label><br>
                                                <input type="file" class="form-control" name="Picture1">
                                                <label>Picture 2 </label><br>
                                                <input type="file" class="form-control" name="Picture2">
                                                <label>Picture 3 </label><br>
                                                <input type="file" class="form-control" name="Picture3">

                                            </div>
                                            <div class="form-group">
                                                <label>Year</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Please Enter your Cost" name="Year">
                                            </div>
                                            <div class="form-group">
                                                <label>Movie Category</label>
                                                <select class="form-control" name="Catagory">
                                                    <option value="1">Category 1</option>
                                                </select>

                                            </div>
                                            <div class="form-group">
                                                <label>Movie Status</label>
                                                <select class="form-control" name="MovieStatus">
                                                    <option value="1">This Week</option>
                                                    <option value="2">Comming Soon</option>
                                                    <option value="3">New</option>
                                                    <option value="4">Old</option>
                                                </select>

                                            </div>
                                            <div style="float:right;">
                                                <?php
                                                if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Ensure the method is GET
                                                    $action = isset($_GET['action']) ? $_GET['action'] : ''; // Check if 'action' exists
                                                
                                                    if ($action === 'edit') { // Check if the action is 'edit'
                                                        ?>
                                                        <button type="submit" class="btn btn-success" name="BtnEditMovie">Edit
                                                            Movie</button>
                                                        <?php
                                                    } else { // Default behavior
                                                        ?>
                                                        <button type="submit" class="btn btn-primary" name="BtnAddMovie">Add
                                                            Movie</button>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <button type="submit" class="btn btn-primary" name="BtnAddMovie">Add

                                                        Movie</button>
                                                    <?php
                                                }
                                                ?>
                                                <a href="<?php echo $_SERVER['PHP_SELF'] ?>" type="reset"
                                                    class="btn btn-danger" name="BtnCancel">Cancel</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bars"></i> Product
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                        id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Movie Name</th>
                                                <th>Length</th>
                                                <th>Year</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php



                                            $Movies = clsMovie::getAllMovies();
                                            if ($Movies) {
                                                foreach ($Movies as $Movie) {
                                                    ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $Movie['MovieName'] ?></td>
                                                        <td><?php echo $Movie['LengthByMin'] ?></td>
                                                        <td><?php echo $Movie['PublishYear'] ?></td>
                                                        <td><?php echo clsCatagory::GetCatagoryById($Movie['main_Cat_ID']) ?>
                                                        </td>
                                                        <td><?php
                                                        if ($Movie['MovieStatus'] == 1) {
                                                            echo 'This Week';
                                                        } elseif ($Movie['MovieStatus'] == 2) {
                                                            echo 'Comming Soon';
                                                        } elseif ($Movie['MovieStatus'] == 3) {
                                                            echo 'New';
                                                        } else {
                                                            echo 'Old';

                                                        }

                                                        ?></td>
                                                        <td>
                                                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>?MovieID=<?php echo $Movie['MovieID']; ?>&action=edit"
                                                                class='btn btn-success'>Edit</a>
                                                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>?MovieID=<?php echo $Movie['MovieID']; ?>&action=delete"
                                                                class='btn btn-danger'>Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <!-- More rows can be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="assets/js/jquery-1.10.2.js"></script>
                <script src="assets/js/bootstrap.min.js"></script>
                <script src="assets/js/jquery.metisMenu.js"></script>
                <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
                <script src="assets/js/morris/morris.js"></script>
                <script src="assets/js/custom.js"></script>
            </div>
        </div>
    </div>
</body>

</html>