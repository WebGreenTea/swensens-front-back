<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

class ActiveSession
{

    public static function exist(int $user, string $token): bool
    {
        $active = DB::connection()->select('SELECT session_id, expired_at FROM active_sessions WHERE user_id = :user_id AND access_token = :token', [
            'user_id' => $user,
            'token' => $token
        ]);
        return (!$active) ? false : true;
    }
}
