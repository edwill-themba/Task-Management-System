<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supervisor;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_name',
        'task_date',
        'task_time',
    ];

    /**
     * Sets timestamps
     */
    public $timestamps = true;

    /**
     * Creates a relationship between supervisor and
     * user models
     */
    public function supervisor()
    {
        return $this->belongsTo('App\Models\Supervisor');
    }

    /**
     * Creates a relationship between task and user models
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
