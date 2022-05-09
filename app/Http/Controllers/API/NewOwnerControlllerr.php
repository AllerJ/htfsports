<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Owner; 
use Cms\Modules\Games\Services\GameService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use Mail;

use Carbon\Carbon;

class NewOwnerControllerr extends Controller 
{
	

	public $successStatus = 200;
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

   
	public function login(Request $request){
		$user = Owner::where('email', $request->email)->first();
		

		if($user){
			if(Hash::check($request->password,$user->password)){

				$lat = $request->lat;
				$lon = $request->lon;

				date_default_timezone_set("America/New_York");
				
				$user->firebase = $request->firebase;
				$user->lastlat = $lat;
				$user->lastlon = $lon;
				$user->phone = date("Y-m-d 00:00:00 T");
				$user->save();
				
				
				$game = $this->gameService->findNearby($lat, $lon);
				
				$success['lat'] =  $lat; 
				$success['lon'] =  $lon; 
				
				$success['today'] = date("Y-m-d 00:00:00");
				$success['token'] =  $user->createToken('HtFS')-> accessToken; 
				$success['name'] = $user->full_name;
				$success['email'] = $user->email;
				$success['pk'] = $user->id;
				$success['avatar'] = $user->avatar;    

				$success['game'] = '0';

				if($game) {        
					$success['game'] = $game->game_id;
				}		

				return response()->json(['success' => $success], $this-> successStatus);
			}else{
				return response()->json(['error'=>'Unauthorised'], 401);
			}
		}
	}

	/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 

		$headers = apache_request_headers();
//		return $headers;
		
		$input = [
			'full_name' => $headers['Fullname'],
			'email' => $headers['Email'],
			'password' => $headers['Password'],
			'game_code' => $headers['Gamecode']
		];
		
		
		$messages = [
		    'full_name.profane' => 'Please use a "Family Friendly" Display Name. ',
		    'full_name.unique' => 'That Display Name is already in use, please pick another one. ',
		    'email.unique' => 'That email address is already in use, please pick another one. '
		    
		];

        $validator = Validator::make($input, [ 
            'full_name' => 'required|profane|unique:owners', 
            'email' => 'required|email|unique:owners', 
            'password' => 'required', 
        ], $messages);
        

		

		if ($validator->fails()) { 
			
//   RETURN Error invalid username or email
            return response()->json(['error'=>$validator->errors()], 401);            
        }

		$game = $this->gameService->findByCode($headers['Gamecode']);	


		if($game) {
		    			date_default_timezone_set('America/New_York');
	        $game_date = explode(' ', $game->game_at);
			$date = new Carbon;
			$lock_after = Carbon::parse($game_date[0].' '.$game->start_at);

			
	//		if($date > $lock_after) {

//   RETURN Error game locked
				
/*
				$success['invalid'] = ["This game has past."];
				return response()->json(['error' => $success], 401);
			} else {
	
*/			
				$input['password'] = bcrypt($input['password']); 
				$user = Owner::create($input); 
				$success['token'] =  $user->createToken('HtFS')-> accessToken; 
				$success['name'] =  $user->full_name;
				$success['pk'] = $user->id;
				$success['game'] = $game['id'];
				
//	RETURN SUCCESS			
				return response()->json(['success'=>$success], $this-> successStatus); 
				
//			}
       	} else {
	       	
//   RETURN Error no game found
	       	$success['invalid'] = ["This game code is invalid."];
				return response()->json(['error' => $success], 401);
	       	
       	}






    }

	public function recoverPassword(Request $request) 
    { 

		$headers = apache_request_headers();

		$email = $headers['Email'];
		$code =  bin2hex(openssl_random_pseudo_bytes(3));
		
		$user = Owner::where('email', $email)->first();
		
		if($user){
			$user->remember_token = $code;
			$user->save();
			Mail::to($user->email)->send(new \App\Mail\PasswordRecover($user));

			$success['success'] = ["Password Recovery Email Sent. Check Email."];
			return response()->json(['success' => $success], $this-> successStatus);

		}
		
		$success['invalid'] = ["No user found by that email address."];
		return response()->json(['error' => $success], 401);

		
		
	}

	public function resetPasswordView($code) 
    { 
	
        return view('cms-frontend::games.password', compact('code'));

	}

	public function resetPassword(Request $request) 
    { 

		$password = $request->password;
		$input = [
			'password' => $password,
		];
	
        $validator = Validator::make($input, [ 
            'password' => ['required', 'min:6'], 
        ]);

		$code = $request->code;
		if($validator->fails()) {
			return self::resetPasswordView($code)->withErrors($validator->errors());
		}


		$user = Owner::where('remember_token', $code)->first();
		$password = bcrypt($password);		
		
		if($user) {

			$user->remember_token = $password;
			$user->password = $password;
			$user->save();
			Mail::to($user->email)->send(new \App\Mail\PasswordReset($user));
			        return view('cms-frontend::games.passwordsaved');

		} else {
			echo "No User Found";
		}

/*

		$password = bcrypt($headers['Password']);		
		$user = Owner::where('recovery_token', $code)->first();
		
		
		if($user){
			$user->remember_token = "";
			$user->password = $password;
			$user->save();
			Mail::to($user->email)->send(new \App\Mail\PasswordReset($user));
			
			$success['success'] = ["Password Reset."];
			return response()->json(['success' => $success], $this-> successStatus);
			
		}
		
		$success['invalid'] = ["There Was an Error."];
		return response()->json(['error' => $success], 401);
*/

		
		
	}
	

	/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 

        return response()->json(['success' => $user], $this-> successStatus); 
    } 
        
}