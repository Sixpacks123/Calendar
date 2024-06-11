<?php
// app/Models/File.php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'link', 'description', 'module_id'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
