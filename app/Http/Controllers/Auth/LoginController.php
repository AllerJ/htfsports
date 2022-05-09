<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Socialite;
use \SocialiteProviders\Manager;
use Illuminate\Http\Request;
use Cms\Modules\Owners\Services\OwnerService;
use Cms\Modules\Games\Services\GameService;





class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide this functionality to your appliations.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OwnerService $owners, GameService $games)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->owner = $owners;
        $this->games = $games;
    }

    /**
     * Check user's role and redirect user based on their role
     * @return
     */
    public function authenticated()
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect('/admin/dashboard');
        }

        return redirect('dashboard');
    }
    
/*

HANDLE INSTAGRAM USERS
    
*/
    public function redirectToProvider()
    {
        return Socialite::driver('instagram')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('instagram')->user();

        $result = $this->owner->findByInsta($user->token);
        
        if($result){
            session([   
                'logged_in' => true, 
                'owner_id' => $result->id, 
                'full_name' => $result->full_name, 
                'avatar' => $result->avatar, 
                'username' => $result->username
                    ]);                

        } else {
            
            $payload = [
                'insta_token' => $user->token,
                'username' => $user->nickname,
                'insta_id' => $user->id,
                'photo' => $user->avatar,
                'full_name' => $user->name,
                'email' => $user->email,
            ];
            
            $result = $this->owner->create($payload);

            
        }
        if($result->email == '') {
            return redirect('login/instagram/extra');
        } else {
            return redirect('/');
        }
    
    }

    public function getExtra()
    {    
        $owner = $this->owner->find(owner('owner_id'));
        return view('cms-frontend::pages.getextra', compact('owner'));
    }
    
    public function saveExtra(Request $request)
    {    
        $owner = $this->owner->find(owner('owner_id'));
        $owner->full_name = $request->full_name;
        $owner->email = $request->email;
        $owner->save();
        session([
            'full_name' => $request->full_name, 
        ]);
        
        return redirect('/');
                    
    }

/*
HANDLE NON INSTAGRAM USERS
*/



    /**
     * Owner Log-In
     *
     * @return \Illuminate\Http\Response
     */
    public function gamelogin(Request $request)
    {

        $result = $this->owner->findByUP($request->email, $request->password);


        if($result){
            session([   
                'logged_in' => true, 
                'owner_id' => $result->id, 
                'full_name' => $result->full_name, 
                'avatar' => $result->avatar, 
                'username' => $result->username,
                'roster' => 'open',
                    ]); 
               
            $game="";
            if($request->lat != "") {
                $game = $this->games->findnearby($request->lat,$request->lng);            
            }

			
            if($game) {
                session([   
                    'game_id' => $game->game_id, 
                ]); 
                return redirect('/games/'.$game->game_code); 
            } else { 
                return redirect('/games/code'); 
            }

        } else {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', 'Account Not Found!');   
            return redirect('/'); 
        }
        
        


        
        //return redirect('/');   
        
        
             
    }


    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function gameShowRegister()
    {

        return view('cms-frontend::pages.register');

    }
    
    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function gamelogout()
    {

        session()->flush();
        return redirect('/');

    }
    
    
    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function gameRegister(Request $request)
    {
        
        $request->validate([
                    'email' => 'required|unique:owners|max:255',
                    'full_name' => 'required',
                    'password' => 'required|confirmed',
                    'password_confirmation' => 'required'
                    ]);

    /**
    * If it passes validation store and automatically log in the Owner
    */

        $payload = [
            'password' => bcrypt($request->password),
            'full_name' => $request->full_name,
            'email' => $request->email,
        ];
        
        $result = $this->owner->create($payload);

        session([   
            'logged_in' => true, 
            'owner_id' => $result->id, 
            'full_name' => $request->full_name, 
            'avatar' => '', 
            'username' => '',
            'roster' => 'open'
                ]);  

            $game="";
            if($request->lat != "") {
                $game = $this->games->findnearby($request->lat,$request->lng);            
            }

            if($game) {
                session([   
                    'game_id' => $game->game_id, 
                ]); 
                return redirect('/games/'.$game->game_code); 
            } else { 
                return redirect('/games/code'); 
            }
    }
}