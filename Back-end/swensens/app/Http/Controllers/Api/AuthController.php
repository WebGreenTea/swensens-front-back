<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use App\Libraries\Util;
use App\Models\ActiveSession;
use App\Models\User;
use Validator;
use Exception;
use stdClass;


class AuthController extends Controller
{
    private const TOKEN_EXP_TIME = 1; // Hours
	private const REFRESH_TOKEN_EXP_TIME = 24; // Hours 

    public function __construct(Request $req)
    {
        $this->req = $req;
        $this->code = 500;
        $this->code_str = 'ERROR';
        $this->success = false;
    }

    public function user(){
        $data = [];
        try {
            $userFetch = User::getUser((int)$this->req->input('auth_user_id'));
            if(!$userFetch){
                $this->code_str = 'AUTH';
                $this->code = 401;
                throw new Exception();
            }
            $userFetch = $userFetch[0];
            //get
            $data = [
                'firstname'=>$userFetch->firstname,
                'lastname'=>$userFetch->surname,
                'phone'=>$userFetch->phone,
                'email'=>$userFetch->email,
                'birth_day'=>substr($userFetch->birthday,0,10),
                'gender'=>$userFetch->gender
            ];
            $this->code_str = 'OK';
            $this->code = 200;
            $this->success = true;
        } catch (Exception $e) {
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function login(){
        $data = [];
        try {
            $validator = Validator::make($this->req->all(), [
                'email' => 'email|regex:/[a-zA-Z0-9._@-]{10,50}/s',
                'password' => 'required|string|min:8',
            ]);
            if($validator->fails()){
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }
            $userFetch = User::where('email',$this->req->input('email'))->get()->toArray();
            // $userFetch = DB::connection()->select('SELECT * FROM users WHERE email = :email',[
            //     'email'=> $this->req->input('email')
            // ]);
            
            if(!$userFetch){
                $this->code_str = 'AUTH';
                $this->code = 401;
                throw new Exception();
            }
            
            $userFetch = $userFetch[0];
            if (!Hash::check($this->req->input('password'), $userFetch['password'])) {
                $this->code_str = 'AUTH';
                $this->code = 401;
                throw new Exception();
            }
            
            //gen token
            $token = $this->genToken($userFetch['user_id']);
            
            $userData = User::getUser($userFetch['user_id']);
            $userData = $userData[0];
            $userData = [
                'firstname'=>$userData->firstname,
                'lastname'=>$userData->surname,
                'phone'=>$userData->phone,
                'email'=>$userData->email,
                'birth_day'=>substr($userData->birthday,0,10),
                'gender'=>$userData->gender
            ];
            $data = [
                'token'=>$token,
                'user'=>$userData
            ];
            // return response()->json(['data'=>$data],200);
            $this->code_str = 'OK';
            $this->code = 200;
            $this->success = true;
        } catch (Exception $e) {
            // $data = $e->getMessage();
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function register(){
        $data = [];
        
        try {
            $validator = Validator::make($this->req->all(), [
                'firstname' => 'required|string|between:1,50',
                'surname' => 'required|string|between:1,50',
                'phone'=>'required|regex:/^0\d{9}$/s',
                'email'=>'required|email|regex:/[a-zA-Z0-9._@-]{10,50}/s',
                'password'=>'required|string|min:8',
                'gender'=>'required|in:Male,Female,Not Specified',
                'birhday'=>'nullable|date|date_format:Y-m-d H:i:s',
                'rule_accept'=>'required|boolean',
                'noti_accept'=>'required|boolean',
            ]);
            if($validator->fails()){
                $this->code_str = 'INPUT';
                $this->code_str = $validator->errors()->getMessages();;
                $this->code = 400;
                throw new Exception();
            }
            $hashed = Hash::make($this->req->input('password'));
    
            $gender = DB::connection()->select('SELECT * FROM genders WHERE title = :gender',[
                'gender'=> $this->req->input('gender')
            ]);
            if(!$gender){
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }
            $gender = $gender[0];
            //insert to DB
            $toDB = [
                'created_at' => Util::now(),
                'firstname' => $this->req->input('firstname'),
                'surname' => $this->req->input('surname'),
                'phone'=> $this->req->input('phone'),
                'email'=> $this->req->input('email'),
                'password'=> $hashed,
                'gender_id'=> $gender->gender_id,
                'birthday'=> empty($this->req->input('birhday')) ? null : $this->req->input('birhday'),
                'rule_accept'=> $this->req->input('rule_accept'),
                'noti_accept'=> $this->req->input('noti_accept'),
            ];
            $user_id = DB::connection()->table('users')->insertGetId($toDB);
            $token = $this->genToken($user_id);

            $userFetch = User::getUser($user_id);
            $userFetch = $userFetch[0];
            $userData = [
                'firstname'=>$userFetch->firstname,
                'lastname'=>$userFetch->surname,
                'phone'=>$userFetch->phone,
                'email'=>$userFetch->email,
                'birth_day'=>substr($userFetch->birthday,0,10),
                'gender'=>$userFetch->gender
            ];
            $data = [
                'token'=>$token,
                'user'=>$userData
            ];
            $this->success = true;
            $this->code = 201;
            $this->code_str = 'CREATED';
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'users_email_unique')) {
                $this->code_str = 'EMAIL_ERROR';
            }else if(str_contains($e->getMessage(), 'users_phone_unique')) {
                $this->code_str = 'PHONE_ERROR';
            }
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function logout(){
        try {
            //delete session
            ActiveSession::where('access_token',$this->req->input('auth_token'))->delete();    

            $this->success = true;
            $this->code = 200;
            $this->code_str = 'OK';
        } catch (Exception $e) {
            $this->success = false;
        }
        return response()->json(['code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function refresh(){
        $session_id = 0;
        $data = [];
        try {

            //check refresh token is exist
            $active = ActiveSession::select('session_id','user_id','expired_at')->where('refresh_token', $this->req->header('Refresh-Token'))->get()->toArray();
            // $active = DB::connection()->select('SELECT session_id, user_id, expired_at, FROM active_sessions WHERE refresh_token = :token',[
            //     'token'=> $this->req->header('Refresh-Token'),
            // ]);

            //if not exist 
            if(!$active){
                $this->code_str = 'AUTH';
                $this->code = 401;
                throw new Exception();
            }
            $session_id = $active[0]['session_id'];

            //decode refresh token
            $headers = new stdClass();
            $auth = JWT::decode($this->req->header('Refresh-Token'), new Key(env('REFRESH_KEY'), 'HS256'), $headers);

            //delete old session
            ActiveSession::where('session_id',$session_id)->delete();
            // DB::connection()->table('active_sessions')->where('session_id',$active[0]->session_id)->delete();

            //check user is exist in db
            $userFetch = User::getUser($auth->user_id);

            if(!$userFetch){
                $this->code_str = 'AUTH';
                $this->code = 401;
                throw new Exception();
            }
            $userFetch = $userFetch[0];
            
            //create new access token and refresh token
            $token = $this->genToken($auth->user_id);

            // $userData = User::getUser($auth->user_id);
            $userData = [
                'firstname'=>$userFetch->firstname,
                'lastname'=>$userFetch->surname,
                'phone'=>$userFetch->phone,
                'email'=>$userFetch->email,
                'birth_day'=>substr($userFetch->birthday,0,10),
                'gender'=>$userFetch->gender
            ];
            $data = [
                'token'=>$token,
                'user'=>$userData
            ];

            $this->success = true;
            $this->code = 200;
            $this->code_str = 'OK';
        } catch (ExpiredException $e) {
            //delete old session
            ActiveSession::where('session_id',$session_id)->delete();
            // DB::connection()->table('active_sessions')->where('session_id',$active[0]->session_id)->delete();

            $this->success = false;
            $this->code = 401;
            $this->code_str = 'AUTH';
        } catch (Exception $e) {
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    private function genToken(int $id) :array
    {
        //create access token
        $expire = time() + (3600 * self::TOKEN_EXP_TIME);
        $token = JWT::encode([
            'iss' => env('APP_NAME'),
            'exp' => $expire,
            'nbf' => time(),
            'user_id' => $id
        ], env('JWT_KEY'),'HS256');

        //create refresh token
        $refreshExpire = time() + (3600 * self::REFRESH_TOKEN_EXP_TIME);
        $refreshToken = JWT::encode([
            'iss' => env('APP_NAME'),
            'exp' => $refreshExpire,
            'nbf' => time(),
            'user_id' => $id
        ], env('REFRESH_KEY'),'HS256');

        //create data and insert to DB
        $data = [
            'created_at' => Util::now(),
            'expired_at' => date('Y-m-d H:i:s', $expire),
            'user_id' => $id,
            'access_token' => $token,
            'refresh_token' => $refreshToken,
        ];

        // DB::connection()->table('active_sessions')->insert($data);
        ActiveSession::insert($data);

        //return
        return [
			'access_token' => $token,
			'refresh_token' => $refreshToken ?? null
		];
    }
}
