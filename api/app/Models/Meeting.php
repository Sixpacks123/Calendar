<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_hour',
        'end_hour',
        'break_time',
        'location',
        'school_id',
        'admin_id',
        'trainer_id',
        'module_id'
    ];

    protected $casts = [
        'start_hour' => 'datetime',
        'end_hour' => 'datetime',
    ];

    // Accessor to ensure the date is always returned in the correct format
    public function getStartHourAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getEndHourAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    // Mutator to ensure the date is always stored in the correct format
    public function setStartHourAttribute($value)
    {
        $this->attributes['start_hour'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setEndHourAttribute($value)
    {
        $this->attributes['end_hour'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    // Relations
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
