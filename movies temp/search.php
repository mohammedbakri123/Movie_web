<?php
session_start();
include 'includes/loggedHeader.php';
include 'autoload.php';
use BusinessLayer\clsMovie;
use BusinessLayer\clsCatagory;

?>

<!-- Search -->

<section class="Search" id="newsletter">
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" class="search" placeholder="Please Enter Movie Name ..." name="Search">
        <input type="submit" value="Search" name="btnSearch" class="btn">
    </form>
</section>


<!-- Result -->
<section class="movies" id="Movies">
    <h2 class="heading">Opening This Week</h2>
    <!-- Movies container -->
    <div class="movies-container">
        <!-- box 1 -->
        <?php
        $moviesThisWeek;
        if (isset($_POST['btnSearch'])) {
            if (!empty($_POST['Search'])) {
                $moviesThisWeek = clsMovie::GetAllMoviesLike($_POST['Search']);
            } else {
                $moviesThisWeek = clsMovie::getAllMovies();
            }

        } else {
            $moviesThisWeek = clsMovie::getAllMovies();
        }

        if (!empty($moviesThisWeek)) {
            //$moviesThisWeek = clsMovie::getAllMovies();
        


            foreach ($moviesThisWeek as $movie) {
                ?>
                <div class="box">
                    <div class="box-img">
                        <a href="play.php?MovieId=<?php echo $movie['MovieID'] ?>"><img
                                src="<?php echo $movie['MoviePoster'] ?>" alt=""></a>
                    </div>
                    <h3><?php echo $movie['MovieName'] ?></h3>
                    <span><?php echo $movie['LengthByMin'] ?> |
                        <?php echo clsCatagory::GetCatagoryById($movie['main_Cat_ID']); ?></span>
                </div>
            <?php }
        } ?>
    </div>
</section>








<?php
include 'includes/footer.php';

?>