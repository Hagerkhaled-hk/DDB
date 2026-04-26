<?php

namespace App\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory;

    public $incrementing = false;
    protected $keyType = "string";
/*     protected $connection = 'branch_A';
 */
    protected $fillable = ['id', 'name', 'grade',"branch"];

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }
}
