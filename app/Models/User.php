<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{

    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'pc_number',
        'group_id',
        'variant',
        'score_correct_task',
        'score_incorrect_task',
        'final_score'
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "remember_token"
    ];


    public function group() {
        return $this->belongsTo(Group::class);
    }
}
