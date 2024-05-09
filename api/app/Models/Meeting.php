<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_hour', 'end_hour', 'break_time', 'location', 'school_id', 'admin_id', 'trainer_id',
    ];

    // Relation avec School
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // Relation avec l'administrateur
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relation avec le trainer
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}
