<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'microsoft_id',
        'isAdmin',
        'is_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The tasks that belong to the user.
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'id_user', 'id_task');
    }

    public function isAdmin()
    {
        return $this->isAdmin;
    }

    public static function nonVerifiedUsers()
    {
        return User::all()->where('is_verified', false);
    }

    public static function verifyUser($id)
    {
        User::where('id', $id)->update(
            array(
                'is_verified' => true,
            )
        );
    }
    
    public static function deleteUser($id)
    {
        User::where('id', $id)->delete();
    }
}
