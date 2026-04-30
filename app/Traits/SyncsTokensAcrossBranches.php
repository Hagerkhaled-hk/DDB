<?php
namespace App\Traits;


use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait SyncsTokensAcrossBranches
{
    protected array $tokenConnections = ['branch_A', 'branch_B'];

    public function createToken(string $name, array $abilities = ['*']): NewAccessToken
    {
        /** @var Model $this */
        $plainText = Str::random(40);
        $hashed    = hash('sha256', $plainText);

        $data = [
            'name'           => $name,
            'token'          => $hashed,
            'abilities'      => json_encode($abilities),
            'tokenable_id'   => $this->getAttribute($this->getKeyName()), // ✅
            'tokenable_type' => static::class,
            'created_at'     => now(),
            'updated_at'     => now(),
        ];

        $firstToken = null;

        foreach ($this->tokenConnections as $connection) {
            $token = (new PersonalAccessToken())->setConnection($connection);
            $token->forceFill($data)->save();

            if (!$firstToken) {
                $firstToken = $token;
            }
        }

        return new NewAccessToken($firstToken, $firstToken->getAttribute('id') . '|' . $plainText);
    }

    public function deleteToken(string $plainText): void
    {
        $hashed = hash('sha256', explode('|', $plainText)[1] ?? $plainText);

        foreach ($this->tokenConnections as $connection) {
            (new PersonalAccessToken())
                ->setConnection($connection)
                ->where('token', $hashed)
                ->delete();
        }
    }
}
