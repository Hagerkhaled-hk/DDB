<?php

namespace App\Models;

use Database\Factories\RecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Record extends Model
{
    /** @use HasFactory<RecordFactory> */
    use HasFactory;

    protected $connection = 'branch_A';

    protected $fillable = ['student_id', 'subject'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
