<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable= [
        'title',
        'description'
    ];

    /**
    * Summary of tasks belongs to User
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function users()
    {
        return $this->belongsToMany(User::class,'task_users','task_id','user_id')
        ->withPivot('status')->withTimestamps();
    }

}
