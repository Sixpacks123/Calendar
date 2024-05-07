<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelescopeEntry extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';
    protected $table = 'telescope_entries';
    public $timestamps = false;
    protected $fillable = [
        'uuid',
        'batch_id',
        'family_hash',
        'should_display_on_index',
        'type',
        'content',
        'created_at',
    ];
}
