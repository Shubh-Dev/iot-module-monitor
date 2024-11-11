<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleHistory extends Model
{
    use HasFactory;
    protected $table = 'module_history';


    // allow fillable 
    protected $fillable = [
        'module_id',
        'measured_value',
        'status',
        'operating_time',
        'data_sent_count',
        'recorded_at'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
