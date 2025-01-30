<?php
namespace DataAccess;
include __DIR__ . '\..\..\autoload.php';
use DataAccess\DatabaseConnection;
use PDO;
use PDOException;

class clsMovieDataAccessLayer
{

    public static function getMovieByName($name, &$Movie): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $query = "SELECT * FROM `movie` WHERE MovieName LIKE :name";
                $stmt = $pdoConnection->prepare($query); // Use prepare to avoid SQL injection
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->execute();
                $movie = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($movie) {
                    $Movie->id = $movie['MovieID'];
                    $Movie->name = $movie['MovieName'];
                    $Movie->Catagory = $movie['main_Cat_ID'];
                    $Movie->publishDate = $movie['publishDate'];
                    $Movie->movieLocation = $movie['MovieLocation'];
                    $Movie->movieLength = $movie['LengthByMin'];
                    $Movie->MoviePoster = $movie['MoviePoster'];
                    $Movie->BigPicture[] = $movie['MovieBigPicture1'];
                    $Movie->BigPicture[] = $movie['MovieBigPicture2'];
                    $Movie->BigPicture[] = $movie['MovieBigPicture3'];
                    $Movie->publishYear = $movie['PublishYear'];
                    $Movie->movieStatus = $movie['MovieStatus'];

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

    }

    public static function getMovieByID($id, &$Movie): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $query = "SELECT * FROM `movie` WHERE MovieID = :id";
                $stmt = $pdoConnection->prepare($query); // Use prepare to avoid SQL injection
                $stmt->bindParam(':name', $id, PDO::PARAM_INT);
                $stmt->execute();
                $movie = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($movie) {
                    $Movie->id = $movie['MovieID'];
                    $Movie->name = $movie['MovieName'];
                    $Movie->Catagory = $movie['main_Cat_ID'];
                    $Movie->publishDate = $movie['publishDate'];
                    $Movie->movieLocation = $movie['MovieLocation'];
                    $Movie->movieLength = $movie['LengthByMin'];
                    $Movie->MoviePoster = $movie['MoviePoster'];
                    $Movie->BigPicture[] = $movie['MovieBigPicture1'];
                    $Movie->BigPicture[] = $movie['MovieBigPicture2'];
                    $Movie->BigPicture[] = $movie['MovieBigPicture3'];
                    $Movie->publishYear = $movie['PublishYear'];
                    $Movie->movieStatus = $movie['MovieStatus'];

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

    }
    public static function getAllMovies(&$movies): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $query = "SELECT * FROM `movie`";
                $stmt = $pdoConnection->prepare($query);
                $stmt->execute();
                $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($movies)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;

        }
    }

    public static function GetAllMoviesLike($name, &$movies)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            $name = '%' . $name . '%';
            if ($pdoConnection !== false) {
                $query = "SELECT * FROM `movie` WHERE MovieName LIKE :name";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->execute();
                $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($movies)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;

        }
    }
    public static function addMovie($movie): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $query = "INSERT INTO `movie`(`MovieName`, `main_Cat_ID`, `publishDate`, `MovieLocation`, `LengthByMin`, `MoviePoster`, `MovieBigPicture1`, `MovieBigPicture2`, `MovieBigPicture3`, `PublishYear` , `MovieStatus`) VALUES (:name, :Catagory, :publishDate, :movieLocation, :movieLength, :MoviePoster, :movieBigPicture1, :movieBigPicture2, :movieBigPicture3, :publishYear , :MovieStatus)";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(':name', ($movie->name), PDO::PARAM_STR);
                $stmt->bindParam(':Catagory', $movie->Catagory, PDO::PARAM_INT);
                $stmt->bindParam(':publishDate', $movie->publishDate, PDO::PARAM_STR);
                $stmt->bindParam(':movieLocation', $movie->movieLocation, PDO::PARAM_STR);
                $stmt->bindParam(':movieLength', $movie->movieLength, PDO::PARAM_INT);
                $stmt->bindParam(':MoviePoster', $movie->MoviePoster, PDO::PARAM_STR);
                $stmt->bindParam(':movieBigPicture1', $movie->BigPicture[0], PDO::PARAM_STR);
                $stmt->bindParam(':movieBigPicture2', $movie->BigPicture[1], PDO::PARAM_STR);
                $stmt->bindParam(':movieBigPicture3', $movie->BigPicture[2], PDO::PARAM_STR);
                $stmt->bindParam(':publishYear', $movie->publishYear, PDO::PARAM_INT);
                $stmt->bindParam(':MovieStatus', $movie->movieStatus, PDO::PARAM_INT);

                return $stmt->execute();
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public static function deleteMovie($id)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $quary = "DELETE FROM `movie` WHERE MovieID = :id";
                $stmt = $pdoConnection->prepare($quary);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                //$stmt->execute();
                return $stmt->execute();
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function GetAllMoviesInYear($Year, &$movies)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "SELECT * FROM `movie` WHERE PublishYear = :Year";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(":Year", $Year, PDO::PARAM_INT);
                $stmt->execute();
                $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($movies)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function GetAllMoviesOfCatagory($catagory, &$movies)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "SELECT * FROM `movie` WHERE main_Cat_ID = :catagory";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(":catagory", $catagory, PDO::PARAM_INT);
                $stmt->execute();
                $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($movies)) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            return false;
        }

    }

    public static function GetMoviesInStatus($status, &$movies)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "SELECT * FROM `movie` WHERE MovieStatus = :status";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(":status", $status, PDO::PARAM_INT);
                $stmt->execute();
                $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($movies)) {
                    return true;
                } else {
                    return false;
                }

            }


        } catch (PDOException $e) {
            return false;
        }


    }
    public static function UpdateMovieByID($movie)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "UPDATE `movie` SET `MovieID`=:id,`MovieName`=:name,`main_Cat_ID`=:catagory,`LengthByMin`=:movieLength,`MovieLocation`=:movieLocation,`publishDate`=:publishDate,`MoviePoster`=:MoviePoster,`MovieBigPicture1`=:movieBigPicture1,`MovieBigPicture2`=:movieBigPicture2,`MovieBigPicture3`=:movieBigPicture3,`PublishYear`=:PublishYear,`MovieStatus`=:MovieStatus WHERE MovieID = :id ";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(':id', $movie->id, PDO::PARAM_INT);
                $stmt->bindParam(':name', $movie->name, PDO::PARAM_STR);
                $stmt->bindParam(':catagory', $movie->Catagory, PDO::PARAM_INT);
                $stmt->bindParam(':publishDate', $movie->publishDate, PDO::PARAM_STR);
                $stmt->bindParam(':movieLocation', $movie->movieLocation, PDO::PARAM_STR);
                $stmt->bindParam(':movieLength', $movie->movieLength, PDO::PARAM_INT);
                $stmt->bindParam(':MoviePoster', $movie->MoviePoster, PDO::PARAM_STR);
                $stmt->bindParam(':movieBigPicture1', $movie->BigPicture[0], PDO::PARAM_STR);
                $stmt->bindParam(':movieBigPicture2', $movie->BigPicture[1], PDO::PARAM_STR);
                $stmt->bindParam(':movieBigPicture3', $movie->BigPicture[2], PDO::PARAM_STR);
                $stmt->bindParam(':publishYear', $movie->publishYear, PDO::PARAM_INT);
                $stmt->bindParam(':MovieStatus', $movie->movieStatus, PDO::PARAM_INT);
                return $stmt->execute();

            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}