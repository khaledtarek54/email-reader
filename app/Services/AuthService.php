<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthService
{
    protected $connection;

    public function __construct()
    {
        // Using the external connection defined in your config
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION', 'transparentDB'));
    }

    public function validateCredentials($username, $password)
    {
        // Assuming your external database has a users table
        $user = $this->connection->table('users')
            ->where('username', $username)
            ->first();

        $hashedInputPassword = md5($password);
        // Check if the user exists and the password matches
        if ($user && $hashedInputPassword === $user->password) {
            return $user; // Password is valid
        } else {
            return null; // Invalid password
        }

        return false;
    }

    public function login($username, $password)
    {
        $user = $this->validateCredentials($username, $password);

        if ($user) {
            // Now, you create the local user in your application, if needed
            $localUser = $this->getOrCreateLocalUser($user);

            // Log in the local user to the Laravel application
            Auth::login($localUser);
            Session::put('user_id', $localUser->external_id);
            return true;
        }

        return false;
    }

    protected function getOrCreateLocalUser($externalUser)
    {
        // You can check if the user exists locally in your application's users table
        $localUser = User::where('external_id', $externalUser->id)->first();

        // If the user doesn't exist locally, create it
        if (!$localUser) {
            $localUser = User::create([
                'name'=>$externalUser->name,
                'email'=>$externalUser->name,
                'password'=>$externalUser->name,
                'username' => $externalUser->name,
                'external_id' => $externalUser->id,
            ]);
        }

        return $localUser;
    }
}
