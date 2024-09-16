<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\BinaryType;
use App\Models\User;

class BinaryFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'binary_type_id',
        'file_name',
        'binary_data',
        'is_deleted',
    ];

    public function binaryType()
    {
        return $this->belongsTo(BinaryType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
