<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Module extends Model
{
    use HasFactory;
    protected $table = 'modules';

    protected  $fillable = [
        'name',
        'type',
        'measured_value',
        'operating_time',
        'data_sent_count',
        'status',
        'last_operated_at'
    ];

    // one to many relationship with ModuleHistory
    public function moduleHistory()
    {
        return $this->hasMany(ModuleHistory::class);
    }
}
