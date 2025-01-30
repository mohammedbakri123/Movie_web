<?php

namespace DataAccess;
use PDO;
use PDOException;

class DatabaseConnection
{
    private static $pdoConnection = null;
    
    public static function getConnection()
    {
        if (self::$pdoConnection === null) {
            $dsn = "mysql:host=localhost;dbname=movies;charset=utf8mb4";
            $username = "root";
            $password = "";

            try {
                self::$pdoConnection = new PDO($dsn, $username, $password);
                self::$pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                return false;
            }
        }
        return self::$pdoConnection;
    }
}