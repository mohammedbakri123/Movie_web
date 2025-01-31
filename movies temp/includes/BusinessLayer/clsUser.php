<?php
namespace BusinessLayer;
require_once __DIR__ . '/../../autoload.php';
use DataAccess\clsUserDataAccessLayer;


use BusinessLayer\mode;
class clsUser
{
    public $mode;
    public $name;
    public $password;
    public $id;
    public $role;
    public $JoinDate;
    public $IsActive;

    public function __construct()
    {
        $this->mode = mode::addNew;
        $this->name = "";
        $this->password = "";
        $this->id = null;
        $this->role = null;
        $this->JoinDate = null;
        $this->IsActive = 2;

    }

    public static function FindByNameAndPassword($name, $password)
    {
        $user = new self();
        if (clsUserDataAccessLayer::getUserByNameAndPassword($name, $password, $user->id, $user->role, $user->JoinDate, $user->IsActive)) {
            $user->mode = mode::Edit;
            $user->name = $name;
            $user->password = $password;
            return $user;
        }
        return null;
    }



    public static function IsUserExistByNameAndPassword($name, $password): bool
    {
        return clsUserDataAccessLayer::IsUserExistByNameAndPassword($name, $password);
    }
    public static function IsUserExistByName($name): bool
    {
        return clsUserDataAccessLayer::isUserExistByName($name);
    }


    public static function FindByID($userID)
    {
        $user = new self();
        if (clsUserDataAccessLayer::getUserByID($user->name, $user->password, $userID, $user->role, $user->JoinDate, $user->IsActive)) {
            $user->mode = mode::Edit;
            $user->id = $userID;
            if ($user->mode->value == 2) {

            }
            return $user;
        }
        return null;
    }

    public static function GetAllUsers(&$users)
    {
        return clsUserDataAccessLayer::GetAllUsers($users);
    }
    private function AddUser()
    {
        return clsUserDataAccessLayer::AddUser($this->name, $this->password, $this->role, $this->IsActive);
    }

    public static function DeleteUser($id)
    {
        return clsUserDataAccessLayer::DeleteUserByID($id);
    }
    private function UpdateUser()
    {
        return clsUserDataAccessLayer::UpdateUser($this->id, $this->name, $this->password, $this->role, $this->IsActive);
    }
    // public static function UpdateUserStat($id, $name, $password, $role, $isActive)
    // {
    //     return clsUserDataAccessLayer::UpdateUser($id, $name, $password, $role, $isActive);
    // }
    public static function SetUserActive($id)
    {
        return clsUserDataAccessLayer::SetUserActive($id);
    }
    public static function SetUserInactive($id)
    {
        return clsUserDataAccessLayer::SetUserInactive($id);
    }
    public function Save()
    {
        if ($this->mode == mode::addNew) {

            return $this->AddUser();

        } elseif ($this->mode == mode::Edit) {
            ;
            return $this->UpdateUser();
        } else {
            return false;
        }
    }


}