<?php

namespace DataAccess;
include __DIR__ . '\..\..\autoload.php';
use DataAccess\DatabaseConnection;
use PDOException;
use PDO;

class clsCatagoryDataAccess
{
    public static function getAllCategories()
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $stmt = $pdoConnection->prepare("SELECT * FROM `catagories`");
                $stmt->execute();
                return $stmt->fetchAll();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

    }

    public static function GetCatagoryById($id, &$name)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $stmt = $pdoConnection->prepare("SELECT * FROM `catagories` WHERE CatagoryID = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch();
                if ($row) {
                    $name = $row['CatagoryName'];
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function addNewCategory($name)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $stmt = $pdoConnection->prepare("INSERT INTO `catagories`(`CatagoryName`) VALUES ('[:name]')");
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                return $stmt->execute();
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public static function GetCatagoryByName($name, &$id)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $stmt = $pdoConnection->prepare("SELECT * FROM `catagories` WHERE CatagoryName = :name");
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch();
                if ($row) {
                    $id = $row['CatagoryID'];
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public static function DeleteCatagoryByID($id)
    {
        $pdoConnection = DatabaseConnection::getConnection();
        try {
            if ($pdoConnection !== false) {
                $stmt = $pdoConnection->prepare("DELETE FROM `catagories` WHERE CatagoryID = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}