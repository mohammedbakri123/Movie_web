<?php
namespace BusinessLayer;
include __DIR__ . "\..\..\autoload.php";
use DataAccess\clsCatagoryDataAccess;

class clsCatagory
{
    public static function GetCatagoryById($id)
    {
        $name = "";
        if (clsCatagoryDataAccess::GetCatagoryById($id, $name)) {
            return $name;
        }
        return null;
    }

    public static function getAllCategories()
    {
        $categories = clsCatagoryDataAccess::getAllCategories();
        if ($categories) {
            return $categories;
        }
        return false;
    }

    public static function addNewCategory($name)
    {
        if (clsCatagoryDataAccess::addNewCategory($name)) {
            return true;
        }
        return false;
    }

    public static function GetCatagoryByName($name)
    {
        $id = 0;
        if (clsCatagoryDataAccess::GetCatagoryByName($name, $id)) {
            return $id;
        }
        return null;
    }
}