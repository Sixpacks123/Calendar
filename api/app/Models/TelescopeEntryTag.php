<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelescopeEntryTag extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'entry_uuid',
        'tag',
    ];
}
