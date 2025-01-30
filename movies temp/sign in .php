<?php include('includes/header.php');
include 'autoload.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['logout'])) {

        session_unset();
        session_destroy();
    }
}
$errorFormSignIn = array();
$errorFormSignUp = array();
$username;
$password;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['SignInSubmit'])) {
        $username = $_POST['SignInusername'];
        $password = $_POST['SignInPassword'];
        $username = htmlspecialchars(strip_tags($username), ENT_QUOTES | ENT_HTML5);
        $password = htmlspecialchars(strip_tags($password), ENT_QUOTES | ENT_HTML5);
    }

}

?>
<main>
    <div class="container">
        <div class="Form login-form">
            <h2>Login</h2>

            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

                <div class="input-box">
                    <i class='bx bxs-user'></i>
                    <label for="#">Username</label>
                    <input type="text" name="SignInusername" placeholder="Enter Your Username*" required>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (isset($_POST['SignInSubmit'])) {
                            if (empty($username) || strlen($username) < 4) {
                                $errorFormSignIn['UserNameInvailed'] = "Username is Unvailed or Too short";
                                echo "<p class = 'Errorp'>{$errorFormSignIn['UserNameInvailed']}</p>";
                            }
                        }
                    }

                    ?>

                </div>
                <div class="input-box">
                    <i class='bx bxs-envelope'></i>
                    <input type="password" name="SignInPassword" placeholder="Enter Your Password*">
                    <label for="#">Password</label>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (isset($_POST['SignInSubmit'])) {
                            if (empty($password) || strlen($password) < 4) {
                                $errorFormSignIn['PassWordInvailed'] = "Password is Unvailed or Too short";
                                echo "<p class = 'Errorp'> {$errorFormSignIn['PassWordInvailed']}</p> ";
                            }
                        }
                    }

                    ?>

                </div>
                <div class="forgot-section">
                    <span><input type="checkbox" name="" id="checked">Remember Me</span>
                    <span><a href="#">Forgot Password ?</a></span>
                </div>
                <button class="signinbtn" type="submit" name="SignInSubmit">Login</button>

            </form>
            <?php

            use BusinessLayer\clsUser;
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['SignInSubmit'])) {
                    $userExist = clsUser::IsUserExistByNameAndPassword($username, $password);
                    if ($userExist && empty($errorFormSignIn)) {
                        $_SESSION['username'] = $username;
                        $_SESSION['password'] = $password;
                        header("Location: index.php");
                        exit();
                    } else {
                        echo "<p class = 'Errorp'> Invalid username or password </p>";
                    }
                }

            }
            ?>

            <p>Or Sign up using</p>
            <div class="social-media">
                <i class='bx bxl-facebook'></i>
                <i class='bx bxl-google'></i>
                <i class='bx bxl-twitter'></i>
            </div>
            <p class="RegisteBtn RegiBtn"><a href="sign Up.php">Register Now</a></p>
        </div>
    </div>
    </div>
</main>

<!-- Footer -->




<!-- Swiper JS -->
<?php include('includes/footer.php'); ?>