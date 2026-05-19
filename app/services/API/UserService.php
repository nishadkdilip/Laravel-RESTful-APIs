<?php  
namespace App\services\API;

use App\Models\User;

class UserService{

    public static function registerUser($request){
       $user = User::create($request);
        return $user;
    }

}
?>