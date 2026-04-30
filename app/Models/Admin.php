<?php

namespace App\Models;

use App\Traits\SyncsTokensAcrossBranches;
use Database\Factories\AdminFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class Admin extends Authenticatable
{
    /** @use HasFactory<AdminFactory> */
use HasApiTokens, SyncsTokensAcrossBranches {
    SyncsTokensAcrossBranches::createToken insteadof HasApiTokens;
}

protected array $tokenConnections = ['branch_A', 'branch_B'];
    protected $fillable = ['name', 'email', 'password', 'branch_id'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

   /*  public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    } */
}