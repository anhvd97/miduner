<?php

namespace App\Models;

use App\Main\Eloquent\Model;


class Post extends Model
{
    public static $table = 'posts';

    public function index()
    {
        //
    }

    public static function who()
    {
        return self::$table;
    }
}
