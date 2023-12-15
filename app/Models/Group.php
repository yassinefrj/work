<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
    ];
    
    public function groupParticipations()
    {
        return $this->hasMany(GroupParticipation::class);
    }
}
