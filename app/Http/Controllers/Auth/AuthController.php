<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\MemberModel as Member;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
            $authUser = $this->findOrCreateUser($user, $provider);

            auth()->login($authUser, true);
            return redirect($this->redirectPath());
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUserMail = User::where('email', $user->email)->first();
        if ($authUserMail) {
            return $authUserMail;
        } else {
            $authUser = User::where('provider_id', $user->id)->first();
            if ($authUser) {
                return $authUser;
            } else {

                $data = User::create([
                    'name'  => $user->name,
                    'email' => !empty($user->email) ? $user->email : '',
                    'password' => bcrypt($user->email),
                    'rec_pass' => Crypt::encryptString($user->email),
                    'provider' => $provider ? $provider : 'umum',
                    'provider_id' => $user->id ? $user->id : '0'
                ]);

                if ($data) {
                    Member::create([
                        'user_id' => $data->id,
                        'nama'  => $user->name,
                        'email' => !empty($user->email) ? $user->email : '',
                        'no_member' => Member::getAutoNoMember(),
                        'status' => 2
                    ]);
                }

                return $data;
            }
        }
    }
}