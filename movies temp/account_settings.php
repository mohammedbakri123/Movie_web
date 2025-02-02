<?php
session_start();
include('includes/loggedHeader.php');
include 'autoload.php';
use BusinessLayer\clsUser;

$crruntUser = new clsUser();

$crruntUser = clsUser::FindByNameAndPassword($_SESSION['username'], $_SESSION['password']);

$errorFormSignUp = array();
$username;
$password;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['SignUpSubmit'])) {
        $SettingsUsername = $_POST['SettingsUsername'];
        $SettingsPassword = $_POST['SettingsPassword'];
        $confirmPassword = $_POST['SettingsConfirmPassword'];
        $SettingsUsername = htmlspecialchars(strip_tags($SettingsUsername), ENT_QUOTES | ENT_HTML5);
        $SettingsPassword = htmlspecialchars(strip_tags($SettingsPassword), ENT_QUOTES | ENT_HTML5);
        $confirmPassword = htmlspecialchars(strip_tags($confirmPassword), ENT_QUOTES | ENT_HTML5);
    }
}




?>
<main>
    <div class="container">
        <div class="Form Register-form active">
            <h2>Account</h2>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="input-box">
                    <i class='bx bxs-user'></i>
                    <label for="#">Username</label>
                    <input value="<?php echo $crruntUser->name; ?>" type="text" name="SettingsUsername"
                        placeholder="Enter Your Username*" readonly>
                    <?php

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (isset($_POST['SignUpSubmit'])) {
                            if (empty($SettingsUsername) || $SettingsUsername != $crruntUser->name) {


                                $errorFormSignUp['UserNameInvailed'] = "username invailed";
                                echo "<p class = 'Errorp'>{$errorFormSignUp['UserNameInvailed']}</p>";
                            }
                        }
                    }

                    ?>
                </div>
                <div class="input-box">
                    <i class='bx bxs-envelope'></i>
                    <input value="<?php echo $crruntUser->password; ?>" type="text" name="SettingsPassword"
                        placeholder="Enter Your Password*" required>
                    <label for="#">Password</label>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (isset($_POST['SignUpSubmit'])) {
                            if (empty($SettingsPassword) || strlen($SettingsPassword) < 4) {
                                $errorFormSignUp['PassWordInvailed'] = "Password is Unvailed or Too short";
                                echo "<p class = 'Errorp'> {$errorFormSignUp['PassWordInvailed']}</p> ";
                            }
                        }
                    }
                    ?>
                </div>
                <div class="input-box">
                    <i class='bx bxs-envelope'></i>
                    <input value="<?php echo $crruntUser->password; ?>" type="text" name="SettingsConfirmPassword"
                        placeholder="Enter Your Password*" required>
                    <label for="#">Confirm Password</label>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (isset($_POST['SignUpSubmit'])) {
                            if ($SettingsPassword != $confirmPassword) {
                                $errorFormSignUp['ConfirmPasswordInvailed'] = "Confirm Password is not match";
                                echo "<p class = 'Errorp'> {$errorFormSignUp['ConfirmPasswordInvailed']}</p> ";
                            }
                        }
                    }
                    ?>
                </div>
                <!-- <div class="forgot-section">
                    <span><input type="checkbox" name="" id="checkded">Remember Me</span>
                    <span><a href="#">Forgot Password ?</a></span>
                </div> -->
                <button class="signinbtn" id="signUpButton" type="submit" name="SignUpSubmit"
                    class="loginBtn">Edit</button>
                <button class="signinbtn" id="signUpButton" type="submit" name="btnDeleteAccount"
                    class="loginBtn">Delete
                    Account</button>
            </form>
            <?php
            //$user = new clsUser();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['btnDeleteAccount'])) {
                    clsUser::DeleteUser($crruntUser->id);
                    session_destroy();
                    header("Location: sign in .php?logout=true");
                    exit;
                }

                if (isset($_POST['SignUpSubmit'])) {
                    $userExistByName = clsUser::IsUserExistByName($SettingsUsername);
                    if (empty($errorFormSignUp)) {
                        $crruntUser->name = $SettingsUsername;
                        $crruntUser->password = $SettingsPassword;
                        $crruntUser->role = 2;
                        $crruntUser->IsActive = 1;
                        if ($crruntUser->Save()) {
                            $_SESSION['username'] = $SettingsUsername;
                            $_SESSION['password'] = $SettingsPassword;
                            echo "<p class = 'Successp'> User is Edited successfully </p>";
                            header("Location: " . $_SERVER["PHP_SELF"]);
                            exit;
                        } else {
                            echo "<p class = 'Errorp'> Error in adding user </p>";
                        }

                    } else {
                        echo "<p class = 'Errorp'> Username is already exist </p>";
                    }
                }
            }



            ?>
            <!-- <p>Or Sign up using</p>
            <div class="social-media">
                <i class='bx bxl-facebook'></i>
                <i class='bx bxl-google'></i>
                <i class='bx bxl-twitter'></i>
            </div>
            <p class="LoginBtn"><a href="Sign in .php">Login Now</a></p>
        </div>
    </div> -->
</main>

<!-- Footer -->




<!-- Swiper JS -->
<?php include('includes/footer.php'); ?>