<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken;

class MultibranchAccessToken extends PersonalAccessToken
{
    // ✅ Force correct table name
    protected $table = 'personal_access_tokens';

    public static function findToken($token): ?static
    {
        $connections = ['branch_A', 'branch_B'];

        $hashed = hash('sha256', explode('|', $token)[1] ?? $token);

        foreach ($connections as $connection) {
            $found = (new static())
                ->setConnection($connection)
                ->newQuery()                   // ✅ fresh query on correct connection
                ->where('token', $hashed)
                ->first();

            if ($found) {
                return $found;
            }
        }

        return null;
    }

    // ✅ Fixed tokenable — don't use associate(), just let morphTo work normally
    public function tokenable()
    {
        return $this->morphTo('tokenable', 'tokenable_type', 'tokenable_id');
    }
}
