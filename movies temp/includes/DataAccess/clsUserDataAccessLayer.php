<?php
namespace DataAccess;
require_once __DIR__ . '/../../autoload.php';
use DataAccess\DatabaseConnection;
use PDO;
use PDOException;
class clsUserDataAccessLayer
{

    public static function getUserByNameAndPassword($nama, $password, &$ID, &$role, &$joinDate, &$IsActive): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        if ($pdoConnection !== false) {
            try {
                $query = "SELECT * FROM user WHERE username = :username AND password = :password";
                $stmt = $pdoConnection->prepare($query); // Use prepare to avoid SQL injection
                $stmt->bindParam(':username', $nama, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    $ID = $user['UserID'];
                    $role = $user['Role'];
                    $joinDate = $user['JoinDate'];
                    $IsActive = $user['IsActive'];
                    return true;
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        } else {
            return false;
        }

        return false;
    }

    public static function IsUserExistByNameAndPassword($nama, $password): bool
    {

        $pdoConnection = DatabaseConnection::getConnection();
        if ($pdoConnection != false) {
            try {
                $query = "SELECT * FROM user WHERE username = :username AND Password = :password";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(':username', $nama, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                // Fetch all results as an associative array
                $stmt->execute();
                return $stmt->fetchColumn() > 0;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        } else {
            return false;
        }
    }
    public static function isUserExistByName($name): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {

                $query = "SELECT * FROM user WHERE username = :username";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(':username', $name, PDO::PARAM_STR);
                // Fetch all results as an associative array
                $stmt->execute();
                return $stmt->fetchColumn() > 0;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }


    }

    public static function getAllUsers(&$users): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $query = "SELECT * FROM user";
                $stmt = $pdoConnection->prepare($query);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public static function getUserByID(&$nama, &$password, $ID, &$role, &$joinDate, &$IsActive): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "SELECT * FROM user WHERE UserID = :id";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam("id", $ID, PDO::PARAM_INT);
                $stmt->execute();
                // Fetch all results as an associative array
                $User = $stmt->fetch(PDO::FETCH_ASSOC);
                if (count($User) > 0) {
                    $nama = $User['UserName'];
                    $password = $User['Password'];
                    $role = $User['Role'];
                    $joinDate = $User['JoinDate'];
                    $IsActive = $User['IsActive'];
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

    public static function AddUser($username, $password, $role, $IsActive): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {

                $query = "INSERT INTO `user` (`UserName`, `Role`, `Password` , `IsActive`) VALUES (:username, :role, :password , :Active)";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':role', $role, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':Active', $IsActive, PDO::PARAM_INT);
                // Fetch all results as an associative array
                return $stmt->execute();


            } else {
                return false;
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }


    }
    public static function DeleteUserByID($userId): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "DELETE FROM `user` WHERE `UserID` = :id";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

                // Execute the query and return whether it was successful
                return $stmt->execute();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public static function UpdateUser($userId, $username, $password, $role, $IsActive): bool
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "UPDATE `user` SET `UserName` = :username, `Role` = :role,`IsActive` = :Active ,`Password` = :password WHERE `UserID` = :id";
                $stmt = $pdoConnection->prepare($query);

                // Bind the parameters
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':role', $role, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':Active', $IsActive, PDO::PARAM_INT);

                // Execute the statement and return the result
                return $stmt->execute();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

    }
    public static function SetUserActive($id)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "UPDATE `user` SET `IsActive` = 1 WHERE `UserID` = :id";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function SetUserInactive($id)
    {

        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection != false) {
                $query = "UPDATE `user` SET `IsActive` = 2 WHERE `UserID` = :id";
                $stmt = $pdoConnection->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}