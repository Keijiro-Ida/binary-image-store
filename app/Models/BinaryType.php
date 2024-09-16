<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\BinaryFile;

class BinaryType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
        'description',
        'type_name_jp',
        'description_jp',
    ];

    public function binaryFiles()
    {
        return $this->hasMany(BinaryFile::class);
    }
}
