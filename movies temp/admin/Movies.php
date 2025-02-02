<?php
include '../autoload.php';
use BusinessLayer\clsCatagory;
use BusinessLayer\clsMovie;
use BusinessLayer\clsUser;
session_start();
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

function encryptParam($value)
{
    $key = 'Mohammed'; // Replace with a secure key
    $cipher = 'aes-256-cbc';
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($value, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptParam($value)
{
    $key = 'Mohammed';
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
                                <i class="fa fa-plus-circle"></i>
                                <?php echo (isset($action) && $action === 'edit') ? 'Edit' : 'Add New'; ?> Movie
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        $action = isset($_GET['action']) ? $_GET['action'] : '';
                                        $encryptedMovieID = isset($_GET['MovieID']) ? $_GET['MovieID'] : '';

                                        $MovieID = isset($encryptedMovieID) ? decryptParam($encryptedMovieID) : '';


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
                                            $AddingErrors = array();
                                            $uploadErrors = array();

                                            // Text field validations
                                            $name = isset($_POST['Name']) ? $_POST['Name'] : '';
                                            if (empty($name)) {
                                                $AddingErrors['emptyName'] = 'Name is required.';
                                            }

                                            $length = isset($_POST['Length']) ? $_POST['Length'] : '';
                                            if (empty($length) || ($length < 0 || $length > 400)) {
                                                $AddingErrors['InVailedLength'] = 'Length is Invalid.';
                                            }

                                            $year = isset($_POST['Year']) ? $_POST['Year'] : '';
                                            if (empty($year) || ($year < 1900 || $year > 2100)) {
                                                $AddingErrors['InVailedYear'] = 'Year is Invalid.';
                                            }

                                            $category = isset($_POST['Catagory']) ? $_POST['Catagory'] : '';
                                            $status = isset($_POST['MovieStatus']) ? $_POST['MovieStatus'] : '';
                                            $movieID = isset($_POST['MovieID']) ? $_POST['MovieID'] : '';
                                            // File validation and processing function
                                            function processUpload($fileKey, $allowedTypes, $maxSize, $relativeDir, $required = true)
                                            {
                                                global $uploadErrors;

                                                if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] === UPLOAD_ERR_NO_FILE) {
                                                    if ($required)
                                                        return ['error' => "$fileKey is required."];
                                                    return ['path' => null]; // Optional file not provided
                                                }

                                                $file = $_FILES[$fileKey];

                                                if ($file['error'] !== UPLOAD_ERR_OK) {
                                                    return ['error' => "Error uploading $fileKey."];
                                                }

                                                // Validate MIME type using file info
                                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                                $mime = finfo_file($finfo, $file['tmp_name']);
                                                finfo_close($finfo);

                                                if (!in_array($mime, $allowedTypes)) {
                                                    return ['error' => "Invalid file type for $fileKey."];
                                                }

                                                if ($file['size'] > $maxSize) {
                                                    return ['error' => "File size too large for $fileKey."];
                                                }

                                                // Define absolute and relative paths
                                                $absoluteDir = "../" . $relativeDir; // Moves file to ../img/ or ../videos/
                                                $dbPathDir = $relativeDir; // Saves as img/filename.jpg or videos/filename.mp4
                                        
                                                // Create the target directory if it doesn’t exist
                                                if (!is_dir($absoluteDir)) {
                                                    mkdir($absoluteDir, 0755, true);
                                                }

                                                // Generate a unique filename
                                                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                                                $newFilename = uniqid() . '.' . $extension;

                                                // Full paths
                                                $absolutePath = $absoluteDir . $newFilename; // Where the file is actually stored
                                                $dbPath = $dbPathDir . $newFilename; // What is stored in the DB
                                        
                                                // Move uploaded file
                                                if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
                                                    return ['error' => "Failed to move uploaded $fileKey."];
                                                }

                                                return ['path' => $dbPath]; // Return only the relative path for DB storage
                                            }


                                            if (isset($_POST['BtnAddMovie'])) {
                                                $newMovie = new clsMovie();
                                                $newMovie->name = $name;
                                                $newMovie->movieLength = $length;
                                                $newMovie->publishYear = $year;
                                                $newMovie->Catagory = $category;
                                                $newMovie->movieStatus = $status;

                                                // Process Movie File (required)
                                                $movieLocation = processUpload(
                                                    'movieLocation',
                                                    ['video/mp4', 'video/webm', 'video/ogg', 'video/mkv'],
                                                    5000 * 1024 * 1024, // 500MB
                                                    'videos/'
                                                );
                                                if (isset($movieLocation['error'])) {
                                                    $uploadErrors[] = $movieLocation['error'];
                                                } else {
                                                    $newMovie->movieLocation = $movieLocation['path'];
                                                }

                                                // Process Poster (required)
                                                $poster = processUpload(
                                                    'Poster',
                                                    ['image/jpeg', 'image/png', 'image/gif'],
                                                    50 * 1024 * 1024, // 5MB
                                                    'img/',
                                                    true
                                                );
                                                if (isset($poster['error'])) {
                                                    $uploadErrors[] = $poster['error'];
                                                } else {
                                                    $newMovie->MoviePoster = $poster['path'];
                                                }

                                                // Process Pictures (optional)
                                                $pictures = [];
                                                foreach (['Picture1', 'Picture2', 'Picture3'] as $pic) {
                                                    $result = processUpload(
                                                        $pic,
                                                        ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'],
                                                        50 * 1024 * 1024,
                                                        'img/',
                                                        true
                                                    );
                                                    if (isset($result['error'])) {
                                                        $uploadErrors[] = $result['error'];
                                                    } elseif ($result['path']) {
                                                        $newMovie->BigPicture[] = $result['path'];
                                                    }
                                                }

                                                if (empty($AddingErrors) && empty($uploadErrors)) {
                                                    if ($newMovie->Save()) {
                                                        header("Location: Movies.php");
                                                        exit;
                                                    }
                                                }
                                            } elseif (isset($_POST['BtnEditMovie'])) {
                                                $existingMovie = clsMovie::getMovieById($_POST['MovieID']);
                                                $existingMovie->name = $name;
                                                $existingMovie->movieLength = $length;
                                                $existingMovie->publishYear = $year;
                                                $existingMovie->Catagory = $category;
                                                $existingMovie->movieStatus = $status;

                                                // Process Movie File (optional in edit)
                                                $movieLocation = processUpload(
                                                    'movieLocation',
                                                    ['video/mp4', 'video/webm', 'video/ogg'],
                                                    2000 * 1024 * 1024,
                                                    'videos/',
                                                    false
                                                );
                                                if (isset($movieLocation['error'])) {
                                                    $uploadErrors[] = $movieLocation['error'];
                                                } elseif ($movieLocation['path']) {
                                                    // Delete old movie file
                                                    if ($existingMovie->movieLocation && file_exists($existingMovie->movieLocation)) {
                                                        unlink($existingMovie->movieLocation);
                                                    }
                                                    $existingMovie->movieLocation = $movieLocation['path'];
                                                }

                                                // Process Poster (optional in edit)
                                                $poster = processUpload(
                                                    'Poster',
                                                    ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'],
                                                    50 * 1024 * 1024,
                                                    'img/',
                                                    true
                                                );
                                                if (isset($poster['error'])) {
                                                    $uploadErrors[] = $poster['error'];
                                                } elseif ($poster['path']) {
                                                    // Delete old poster
                                                    if ($existingMovie->MoviePoster && file_exists($existingMovie->MoviePoster)) {
                                                        unlink($existingMovie->MoviePoster);
                                                    }
                                                    $existingMovie->MoviePoster = $poster['path'];
                                                }

                                                // Process Pictures (optional)
                                                foreach (['Picture1', 'Picture2', 'Picture3'] as $pic) {
                                                    $result = processUpload(
                                                        $pic,
                                                        ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'],
                                                        50 * 1024 * 1024,
                                                        'img/',
                                                        true
                                                    );
                                                    if (isset($result['error'])) {
                                                        $uploadErrors[] = $result['error'];
                                                    } elseif ($result['path']) {
                                                        // Delete old picture if exists
                                                        $oldPic = $existingMovie->{$pic . 'Path'};
                                                        if ($oldPic && file_exists($oldPic)) {
                                                            unlink($oldPic);
                                                        }
                                                        $existingMovie->BigPicture[] = $result['path'];
                                                    }
                                                }

                                                if (empty($AddingErrors) && empty($uploadErrors)) {
                                                    if ($existingMovie->Save()) {
                                                        header("Location: Movies.php");
                                                        exit;
                                                    }
                                                }
                                            }

                                            // Display errors if any
                                            foreach ($AddingErrors as $error) {
                                                echo "<div class='alert alert-danger'>$error</div>";
                                            }
                                            foreach ($uploadErrors as $error) {
                                                echo "<div class='alert alert-danger'>$error</div>";
                                            }
                                        }
                                        ?>



                                        <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>"
                                            role="form" method="POST">
                                            <?php if (isset($action) && $action === 'edit'): ?>
                                            <div class="form-group">
                                                <label>Movie ID</label>
                                                <input class="form-control" readonly type="text" name="MovieID"
                                                    value="<?php echo $MovieID; ?>">
                                            </div>
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <label>Movie Name</label>
                                                <input type="text" placeholder="Please Enter Movie Name"
                                                    class="form-control" name="Name"
                                                    value="<?php echo isset($movie) ? htmlspecialchars($movie->name) : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Length in Mins</label>
                                                <input type="number" placeholder="Please Enter a Number"
                                                    class="form-control" name="Length"
                                                    value="<?php echo isset($movie) ? htmlspecialchars($movie->movieLength) : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Year</label>
                                                <input type="number" class="form-control"
                                                    placeholder="Please Enter Year" name="Year"
                                                    value="<?php echo isset($movie) ? htmlspecialchars($movie->publishYear) : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>The Movie</label><br>
                                                <input type="file" class="form-control" name="movieLocation">
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
                                                <label>Movie Category</label>
                                                <select class="form-control" name="Catagory">
                                                    <?php
                                                    $catagoris = clsCatagory::getAllCategories();
                                                    if (isset($catagoris)) {
                                                        
                                                        foreach ($catagoris as $catagory) {
                                                            $selected = (isset($movie) && $movie->Catagory == $cat['CatID']) ? 'selected' : '';
                                                            ?>


                                                    <option value="<?php echo $catagory['CatagoryID'] ?>"
                                                        <?php echo $selected ?>>
                                                        <?php echo $catagory['CatagoryName'] ?>
                                                    </option>
                                                    <?php }
                                                    } ?>
                                                </select>

                                            </div>
                                            <div class="form-group">
                                                <label>Movie Status</label>
                                                <select class="form-control" name="MovieStatus">
                                                    <option value="1"
                                                        <?php echo (isset($movie) && $movie->movieStatus == 1) ? 'selected' : ''; ?>>
                                                        This Week</option>
                                                    <option value="2"
                                                        <?php echo (isset($movie) && $movie->movieStatus == 2) ? 'selected' : ''; ?>>
                                                        Coming Soon</option>
                                                    <option value="3"
                                                        <?php echo (isset($movie) && $movie->movieStatus == 3) ? 'selected' : ''; ?>>
                                                        New</option>
                                                    <option value="4"
                                                        <?php echo (isset($movie) && $movie->movieStatus == 4) ? 'selected' : ''; ?>>
                                                        Old</option>
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
                                <i class="fa fa-bars"></i> Movie
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
                                                    <a href="Movies.php?MovieID=<?php echo encryptParam($Movie['MovieID']); ?>&action=edit"
                                                        class="btn btn-success">Edit</a>

                                                    <a href="#"
                                                        onclick="confirmDelete('<?php echo encryptParam($Movie['MovieID']); ?>')"
                                                        class="btn btn-danger">Delete</a>
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
                <script>
                function confirmDelete(movieId) {
                    let confirmAction = confirm("Are you sure you want to delete this movie?");
                    if (confirmAction) {
                        window.location.href = "Movies.php?MovieID=" + movieId + "&action=delete";
                    }
                }
                </script>

            </div>
        </div>
    </div>
</body>

</html>