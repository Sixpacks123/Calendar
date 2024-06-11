<?php

// app/Models/Module.php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'syllabus', 'hourly_rate', 'promotion', 'comment'
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }
    
}
