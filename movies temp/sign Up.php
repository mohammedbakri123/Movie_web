<?php include('includes/header.php');
include 'autoload.php';
use BusinessLayer\clsUser;
session_start();
$errorFormSignUp = array();
$username;
$password;
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['SignInSubmit'])){
        $username = $_POST['SignInusername'];
        $password = $_POST['SignInPassword'];
        $username =  htmlspecialchars(strip_tags( $username), ENT_QUOTES | ENT_HTML5);
        $password = htmlspecialchars(strip_tags( $password), ENT_QUOTES | ENT_HTML5);
    }
    if(isset($_POST['SignUpSubmit'])){
        $SignUpusername = $_POST['SignUpUsername'];
        $SignUppassword = $_POST['SignUpPassword'];
        $confirmPassword = $_POST['SignUpConfirmPassword'];
        $SignUpusername =  htmlspecialchars(strip_tags( $SignUpusername), ENT_QUOTES | ENT_HTML5);
        $SignUppassword = htmlspecialchars(strip_tags( $SignUppassword), ENT_QUOTES | ENT_HTML5);
        $confirmPassword = htmlspecialchars(strip_tags( $confirmPassword), ENT_QUOTES | ENT_HTML5);
    }

   

}

?>
<main>
    <div class="container">
        <div class="Form Register-form active">
            <h2>Register</h2>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="input-box">
                    <i class='bx bxs-user'></i>
                    <label for="#">Username</label>
                    <input type="text" name="SignUpUsername" placeholder="Enter Your Username*" required>
                    <?php
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if(isset($_POST['SignUpSubmit']))
                        {
                            if(empty($SignUpusername) || strlen($SignUpusername) < 4)
                            {
                                $errorFormSignUp['UserNameInvailed'] = "Username is Unvailed or Too short";
                                echo "<p class = 'Errorp'>{$errorFormSignUp['UserNameInvailed']}</p>";
                            }
                        }
                    }
                    
                    ?>
                </div>
                <div class="input-box">
                    <i class='bx bxs-envelope'></i>
                    <input type="password" name="SignUpPassword" placeholder="Enter Your Password*" required>
                    <label for="#">Password</label>
                    <?php
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if(isset($_POST['SignUpSubmit']))
                        {
                            if(empty($SignUppassword) || strlen($SignUppassword) < 4)
                            {
                                $errorFormSignUp['PassWordInvailed'] = "Password is Unvailed or Too short";
                                echo "<p class = 'Errorp'> {$errorFormSignUp['PassWordInvailed']}</p> ";
                            }
                        }
                    }
                    ?>
                </div>
                <div class="input-box">
                    <i class='bx bxs-envelope'></i>
                    <input type="password" name="SignUpConfirmPassword" placeholder="Enter Your Password*" required>
                    <label for="#">Confirm Password</label>
                    <?php
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if(isset($_POST['SignUpSubmit']))
                        {
                            if($SignUppassword!= $confirmPassword)
                            {
                                $errorFormSignUp['ConfirmPasswordInvailed'] = "Confirm Password is not match";
                                echo "<p class = 'Errorp'> {$errorFormSignUp['ConfirmPasswordInvailed']}</p> ";
                            }
                        }
                    }                    
                    ?>
                </div>
                <div class="forgot-section">
                    <span><input type="checkbox" name="" id="checkded">Remember Me</span>
                    <span><a href="#">Forgot Password ?</a></span>
                </div>
                <button class="signinbtn" id="signUpButton" type="submit" name="SignUpSubmit"
                    class="loginBtn">Register</button>
            </form>
            <?php
            $user = new clsUser();
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(isset($_POST['SignUpSubmit']))
                {      
                    $userExistByName = clsUser::IsUserExistByName($SignUpusername);
                    if(!$userExistByName && empty($errorFormSignUp))
                    {
                        $user->name = $SignUpusername;
                        $user->password = $SignUppassword;
                        $user->role = 2;
                        $user->IsActive = 1;
                        if($user->Save())
                        {
                            echo "<p class = 'Successp'> User is added successfully </p>";
                        }
                        else
                        {
                            echo "<p class = 'Errorp'> Error in adding user </p>";
                        }
                        
                    }
                    else
                    {
                        echo "<p class = 'Errorp'> Username is already exist </p>";
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
            <p class="LoginBtn"><a href="Sign in .php">Login Now</a></p>
        </div>
    </div>
</main>

<!-- Footer -->




<!-- Swiper JS -->
<?php include('includes/footer.php'); ?>