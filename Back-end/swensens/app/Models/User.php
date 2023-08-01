<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    protected $table = 'users';
    protected  $primaryKey = 'user_id';

    public static function getUser(int $id){
        $user = DB::connection()->select('SELECT u.firstname ,u.surname ,u.phone ,u.email, u.birthday , g.title AS gender FROM users u INNER JOIN genders g ON u.gender_id=g.gender_id  WHERE user_id = :user_id',['user_id'=> $id]);
        return $user;
    }
}
