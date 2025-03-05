<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {
    use HasFactory;

    protected $fillable = ['name', 'score_task_1', 'score_task_2', 'score_task_3', 'score_task_4', 'total_score'];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    public function students() {
        return $this->hasMany(User::class);
    }
}
