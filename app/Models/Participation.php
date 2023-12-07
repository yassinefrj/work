<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use function Laravel\Prompts\select;

class Participation extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_task',
        'id_user',
    ];

    public static function add($id_task, $id_user)
    {
        return DB::insert(
            "insert into participations (id_task, id_user) values (?,?)",
            [$id_task, $id_user]
        );
    }
}
