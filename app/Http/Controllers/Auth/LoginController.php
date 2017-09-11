<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\SocialProvider;
use Socialite;
use Illuminate\Support\Facades\Auth;
use App\Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'phone_number';
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
     public function redirectToProvider($provider)
     {
         return Socialite::driver($provider)->redirect();
     }

    /**
    * Obtain the user information from Facebook.
    *
    * @return Response
    */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        
                $authUser = $this->findOrCreateUser($user, $provider);
                Auth::login($authUser, true);
                return redirect($this->redirectTo);
    }

    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'user_name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
    }
}
