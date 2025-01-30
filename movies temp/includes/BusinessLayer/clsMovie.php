<?php
namespace BusinessLayer;
include __DIR__ . "\..\..\autoload.php";
use DataAccess\clsMovieDataAccessLayer;
// enum mode: int
// {
//     case Edit = 2;
//     case addNew = 1;

// }

class clsMovie
{
    public $mode;
    public $id;
    public $name;
    public $Catagory;
    public $publishDate;
    public $movieLocation;
    public $movieLength;
    public $MoviePoster;
    public $BigPicture;
    public $publishYear;
    public $movieStatus;

    public function __construct()
    {
        $this->mode = mode::addNew;
        $this->id = null;
        $this->name = "";
        $this->Catagory = "";
        $this->publishDate = null;
        $this->movieLocation = "";
        $this->movieLength = 0;
        $this->MoviePoster = "";
        $this->BigPicture = array();
        $this->publishYear = 0;
        $this->movieStatus = 0;

    }
    public static function getAllMovies()
    {
        $movies = array();
        if (clsMovieDataAccessLayer::getAllMovies($movies)) {
            return $movies;
        }
        return null;
    }

    public static function GetAllMoviesLike($name)
    {
        $movies = array();
        if (clsMovieDataAccessLayer::GetAllMoviesLike($name, $movies)) {
            return $movies;
        }
        return null;
    }
    public static function getMovieByname($name)
    {
        $movie = new clsMovie();
        if (clsMovieDataAccessLayer::getMovieByName($name, $movie)) {
            $movie->mode = mode::Edit;
            return $movie;
        }
        return null;
    }
    public static function getMovieById($id)
    {
        $movie = new clsMovie();
        if (clsMovieDataAccessLayer::getMovieByID($id, $movie)) {
            $movie->mode = mode::Edit;
            return $movie;
        }
        return null;
    }
    public static function getAllMoviesInYear($year)
    {
        $movies = array();
        if (clsMovieDataAccessLayer::getAllMoviesInYear($year, $movies)) {
            return $movies;
        }
        return null;
    }
    public static function getAllMoviesOfCategory($category)
    {
        $movies = array();
        if (clsMovieDataAccessLayer::GetAllMoviesOfCatagory($category, $movies)) {
            return $movies;
        }
        return null;
    }

    public static function GetAllMoviesInStatus($status)
    {
        $movies = array();
        if (clsMovieDataAccessLayer::GetMoviesInStatus($status, $movies)) {
            return $movies;
        }
        return null;
    }

    public static function deleteMovie($id)
    {
        return clsMovieDataAccessLayer::deleteMovie($id);
    }

    private function AddMovie()
    {
        return clsMovieDataAccessLayer::AddMovie($this);
    }
    private function UpdateMovie()
    {
        return clsMovieDataAccessLayer::UpdateMovieByID($this);
    }
    public function Save()
    {
        if ($this->mode == mode::addNew) {
            return $this->AddMovie();
        } else {
            return $this->UpdateMovie();
        }
    }






}