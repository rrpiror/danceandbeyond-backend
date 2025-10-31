<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'organisations';

    protected $fillable = [
        'user_id',
        'website',
        'description',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
