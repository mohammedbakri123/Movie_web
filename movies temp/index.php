<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    
    include('includes/loggedHeader.php');
} else {
    include('includes/header.php');
}
include "autoload.php";
use BusinessLayer\clsMovie;
use BusinessLayer\clsCatagory;

?>
<!-- Home -->

<section class="home swiper" id="home">
    <!-- slide 1 -->
    <div class="swiper-wrapper">
        <div class="swiper-slide container">
            <img src="img/home1.jpg" alt="">
            <div class="home-text">
                <span>Marvel Universe</span>
                <h1>Venom: Let There <br> Be Carnage</h1>
                <a href="" class="btn">Book Now</a>
                <a href="play.php" class="play">
                    <i class="bx bx-play"></i>
                </a>
            </div>
        </div>
        <!-- slide 1 -->
        <div class="swiper-slide container">
            <img src="img/home2.jpg" alt="">
            <div class="home-text">
                <span>Marvel Universe</span>
                <h1>Avengers: <br> Infinity War</h1>
                <a href="" class="btn">Book Now</a>
                <a href="play.php" class="play">
                    <i class="bx bx-play"></i>
                </a>
            </div>
        </div>
        <!-- slide 3 -->
        <div class="swiper-slide container">
            <img src="img/home3.jpg" alt="">
            <div class="home-text">
                <span>Marvel Universe</span>
                <h1>Spider-Man: <br> Far From Home</h1>
                <a href="" class="btn">Book Now</a>
                <a href="play.php" class="play">
                    <i class="bx bx-play"></i>
                </a>
            </div>
        </div>

    </div>

    <div class="swiper-pagination"></div>
</section>

<!-- Movies -->

<section class="movies" id="Movies">
    <h2 class="heading">Opening This Week</h2>
    <!-- Movies container -->
    <div class="movies-container">
        <!-- box 1 -->
        <?php $moviesThisWeek = clsMovie::GetAllMoviesInStatus(1);
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
        <?php } ?>
    </div>
</section>

<!-- Coming -->

<section class="coming" id="coming">
    <h2 class="heading">Coming Soon</h2>
    <!-- Coming container -->
    <div class="coming-container">
        <div class="swiper-wrapper">
            <!-- box 1 -->
            <?php $moviesComming = clsMovie::GetAllMoviesInStatus(2);
            foreach ($moviesComming as $movie) { ?>
            <div class="swiper-slide box">
                <div class="box-img">
                    <a href="play.php?MovieId=<?php echo $movie['MovieID'] ?>"><img
                            src="<?php echo $movie['MoviePoster'] ?>" alt=""></a>
                </div>
                <h3><?php echo $movie['MovieName'] ?></h3>
                <span><?php echo $movie['LengthByMin'] ?> |
                    <?php echo clsCatagory::GetCatagoryById($movie['main_Cat_ID']); ?></span>
            </div>
            <?php } ?>
            <!-- box 2 -->
        </div>
    </div>
</section>

<!-- newsletter -->

<!-- <section class="newsletter" id="newsletter">
    <h2>Subscribe To Get <br> Newsletter</h2>
    <form action="">
        <input type="email" class="email" placeholder="Enter Email..." required>
        <input type="submit" value="Subscribe" class="btn">
    </form>
</section> -->

<!-- Footer -->
<?php include('includes/footer.php'); ?>