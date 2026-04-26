<?php

namespace App\Models;

use Database\Factories\RequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    /** @use HasFactory<RequestFactory> */
    use HasFactory;
    public $incrementing = false;
    protected $keyType = "string";
    protected $fillable = ["id",'student_id', 'from_branch_id', 'to_branch_id', 'status'];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }
}