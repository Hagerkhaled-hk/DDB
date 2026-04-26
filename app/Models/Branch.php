<?php

namespace App\Models;

use Database\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

  /*   public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    } */
}
