<?php

namespace App\Http\Controllers\Auth;

use App\Invitation;
use App\User;

use Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{

    use RegistersUsers;


    protected $redirectTo = '/home';


    public function __construct()
    {
        $this->middleware('guest');
    }

    public function requestInvitation() {
        return view('auth.request');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'     => 'required|string|email|max:255|unique:users|exists:invitations,email',
            'name'      => 'required|string|max:255',
            'password'  => 'required|string|min:6|confirmed',
            'otp'  => 'required|string|min:6',
        ]);
    }


    protected function create(array $data)
    {
       $otp = Invitation::where('email', $data['email'])->value('otp');

       if ($data['otp'] == $otp) {
          return User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'username'     => '',
            'profileImg'     => '',
            'password'  => bcrypt($data['password']),
            'role_id'   => 2
        ]);
       }else{
        return "wrong OTP";
       }
        
    }


    public function showRegistrationForm(Request $request)
    {
        $invitation_token = $request->get('invitation_token');
        $invitation = Invitation::where('invitation_token', $invitation_token)->firstOrFail();
        $email = $invitation->email;

        return view('auth.register', compact('email'));
    }


    public function registered(Request $request, $user)
    {
        $invitation = Invitation::where('email', $user->email)->firstOrFail();
        $invitation->registered_at = $user->created_at;
        $invitation->save();
    }

    public function generateOTP(Request $request){
        try {
            $otps= rand(100000, 999999);
            $email = $request->input('email');

            Invitation::where('email', $email)->update(array('otp' => $otps));
                 
            $data = array('otp'=>$otps);
            Mail::send('otp', $data, function($message) use($email) {
                $message->to($email)->subject('Registration OTP');
                $message->from('saurabhu410@gmail.com','Admin');
            });



           }catch(Exception $e) {
              echo 'Message: ' .$e->getMessage();
            }

    }


}
