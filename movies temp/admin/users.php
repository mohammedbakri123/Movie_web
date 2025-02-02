<?php
include '../autoload.php';
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
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px; display:flex;">
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
                        <h2><i class="fa fa-users"></i> Users</h2>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-plus-circle"></i> Add New User
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="<?php $_SERVER["PHP_SELF"] ?>" role="form"
                                            enctype="multipart/form-data" method="POST">

                                            <?php
                                            $user = new clsUser();
                                            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                                                $id = isset($_GET['UserID']) ? $_GET['UserID'] : '';
                                                $action = isset($_GET['action']) ? $_GET['action'] : '';
                                                if (isset($action)) {
                                                    switch ($action) {
                                                        case 'edit':
                                                            $user = clsUser::FindByID($id);
                                                            $user->id = $id;
                                                            break;
                                                        case 'delete':
                                                            clsUser::DeleteUser($id);
                                                            break;
                                                        case 'activate':
                                                            clsUser::SetUserActive($id);
                                                            break;                                          
                                                        case 'deactivate':
                                                            clsUser::SetUserInactive($id);
                                                            break;                                                    
                                                        default:                                     
                                                        }


                                            }
                                        }
                                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                                $username = htmlspecialchars(strip_tags($_POST['UserName']), ENT_QUOTES | ENT_HTML5);
                                                $password = htmlspecialchars(strip_tags( $_POST['password']), ENT_QUOTES | ENT_HTML5);
                                                $role = $_POST['role_id'];
                                                $isActive = $_POST['IsActive'];

                                                if (isset($_POST['BtnAddUser'])) {
                                                    if (!clsUser::IsUserExistByName($username)) {
                                                        $user->name = $username;
                                                        $user->password = $password;
                                                        $user->role = $role;
                                                        $user->IsActive = $isActive;
                                                        if((empty($username) || $username < 4) && (empty($password)||$password < 4))
                                                        {
                                                            $result = false;
                                                        }
                                                        else{
                                                            $result = $user->Save();
                                                        }
                                                        if ($result) {
                                                            echo '<div class="alert alert-success">User Added Successfully.</div>';
                                                        } else {
                                                            echo '<div class="alert alert-danger">Failed to Add User.<br>Something went Wrong or PassWrod and UserName is Too Short  </div>';
                                                        }
                                                    } else {
                                                        echo '<div class="alert alert-danger">User Name Already Exists.</div>';
                                                    }
                                                } 
                                                elseif (isset($_POST['BtnEditUser'])) {
                                                    $Crruntuser = new clsUser();
                                                    $Crruntuser = clsUser::FindByID($_POST['userID']);
                                                    $username = htmlspecialchars(strip_tags($_POST['UserName']), ENT_QUOTES | ENT_HTML5);
                                                    
                                                    $IdIsSame = $Crruntuser->id == $_POST['userID'];
                                                    $userNameIsSame = ($Crruntuser->name == $username);
                                                    $Crruntuser->password = $password;
                                                    $Crruntuser->role = $role;
                                                    $Crruntuser->IsActive = $isActive;
                                                    if((empty($Crruntuser->name) || $Crruntuser->name < 4) || (empty($Crruntuser->password)||$Crruntuser->password < 4) || !$userNameIsSame || !$IdIsSame)
                                                        {
                                                            $result = false;
                                                        }
                                                        else{   
                                                            $result = $Crruntuser->Save();
                                                        }
                                                            if ($result) {
                                                                echo '<div class="alert alert-success">User Edited Successfully.</div>';
                                                            } 
                                                            else {
                                                                echo '<div class="alert alert-danger">Failed to Edit User.<br>Something went Wrong or PassWrod and UserName is Too Short  </div>';
                                                            }
                                                    
                                                }
                                                elseif(isset($_POST['BtnCancel'])) {
                                                    header('Location: ' . basename($_SERVER['PHP_SELF']));
                                                    exit;                                                }
                                            }
                                            ?>

                                            <div class="form-group">
                                                <label>ID</label>
                                                <input value="<?php if(isset($user->id)){ echo $user->id; } ?>"
                                                    type="text" placeholder="User ID" class="form-control" name="userID"
                                                    readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input value="<?php if(isset($user->name)){ echo $user->name; } ?>"
                                                    type=text" placeholder="Please Enter your Name" class="form-control"
                                                    name="UserName"
                                                    <?php  if($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'edit'){ echo "readonly"; } ?>>
                                                <?php ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input
                                                    value="<?php if(isset($user->password)){ echo $user->password; } ?>"
                                                    type="text" class="form-control" placeholder="Please Enter password"
                                                    name="password">
                                            </div>

                                            <div class="form-group">
                                                <label>User Type</label>
                                                <select class="form-control" name="role_id">
                                                    <option value="1"
                                                        <?php if(isset($user->role)){if($user->role == 1){echo "selected";} } ?>>
                                                        Admin</option>
                                                    <option value="2"
                                                        <?php if(isset($user->role)){if($user->role == 2){echo "selected";} } ?>>
                                                        User</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Activation Status</label>
                                                <select class="form-control" name="IsActive">
                                                    <option value="1"
                                                        <?php if(isset($user->IsActive)){if($user->IsActive == 1){echo "selected";} } ?>>
                                                        Active</option>
                                                    <option value="2"
                                                        <?php if(isset($user->IsActive)){if($user->IsActive == 2){echo "selected";} } ?>>
                                                        Not Active</option>
                                                </select>
                                            </div>
                                            <div style="float:right;">
                                                <?php
                                                if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Ensure the method is GET
                                                    $action = isset($_GET['action']) ? $_GET['action'] : ''; // Check if 'action' exists
                                                
                                                    if ($action === 'edit') { // Check if the action is 'edit'
                                                        ?>
                                                <button type="submit" class="btn btn-success" name="BtnEditUser">Edit
                                                    User</button>
                                                <?php
                                                    } else { // Default behavior
                                                        ?>
                                                <button type="submit" class="btn btn-primary" name="BtnAddUser">Add
                                                    User</button>
                                                <?php
                                                    }
                                                } else {
                                                    ?>
                                                <button type="submit" class="btn btn-primary" name="BtnAddUser">Add

                                                    User</button>
                                                <?php
                                                }
                                                ?>

                                                <a href="<?php echo $_SERVER['PHP_SELF']?>" type="reset"
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
                                <i class="fa fa-users"></i> Users
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                        id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Password</th>
                                                <th>Join Date</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            clsUser::getAllUsers($AllUsers);
                                            foreach ($AllUsers as $User) {
                                                ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $User["UserID"] ?></td>
                                                <td><?php echo $User["UserName"] ?></td>
                                                <td><?php echo $User["Password"] ?></td>
                                                <td><?php echo $User["JoinDate"] ?></td>
                                                <td><?php if ($User["Role"] == 1) {
                                                        echo "Admin";
                                                    } else {
                                                        echo "User";
                                                    } ?>
                                                </td>
                                                <td>

                                                    <a href="<?php echo $_SERVER['PHP_SELF'] ?>?UserID=<?php echo $User['UserID']; ?>&action=edit"
                                                        class='btn btn-success'>Edit</a>
                                                    <a href="<?php echo $_SERVER['PHP_SELF'] ?>?UserID=<?php echo $User['UserID']; ?>&action=delete"
                                                        class='btn btn-danger'>Delete</a>
                                                    <?php if ($User["IsActive"] == 1) { ?>
                                                    <a href="<?php echo $_SERVER['PHP_SELF'] ?>?UserID=<?php echo $User['UserID']; ?>&action=deactivate"
                                                        class='btn btn-primary'>Deactivate</a>
                                                    <?php } elseif ($User["IsActive"] == 2) { ?>
                                                    <a href="<?php echo $_SERVER['PHP_SELF'] ?>?UserID=<?php echo $User['UserID']; ?>&action=activate"
                                                        class='btn btn-warning'>Activate</a>
                                                    <?php } ?>
                                                </td>

                                            </tr>
                                            <?php } ?>
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