<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use App\User;
use HasApiTokens, Notifiable;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Requests\StoreInvitationRequest;
use App\Invitation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;

class ApiController extends Controller
{

    use RegistersUsers;
    
    public function index()
    {
        return User::all();
    }
 
    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user, 201);
    }

    public function inviteusr(Request $request)
    {
        //Invite Table
        $email = $request->input('email');

        $invitation = new Invitation($request->all());
        $invitation->generateInvitationToken();
        $send = $invitation->save();

        if ($send > 0) {

              $token = Invitation::where('email', $email)->value('invitation_token');
              $content = url('/').'/'."register?invitation_token=".$token;

              $data = array('links'=>$content);
              Mail::send('mail', $data, function($message) use($email) {

                 $message->to($email)->subject('User Invites Email');
                 $message->from('admin@gmail.com','Admin');
              });
        }

        $response = "Please Check your Email";
        return response()->json($response, 201);
    }

    public function store(Request $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');
        $otp = $request->input('otp');
     
        $otptb = Invitation::where('email', $email)->value('otp');

        if ($otp == $otptb) {
            $users = User::create([
            'name'      => $name,
            'email'     => $email,
            'username'     => '',
            'profileImg'     => '',
            'password'  => bcrypt($password),
            'role_id'   => 2
            ]);
        }

        return response()->json($users, 201);

    }

    public function update(Request $request, $id)
    {
        $users = User::findOrFail($id);
        $users = $Users->update($request->all());

        return response()->json($users, 200);
    }

    public function delete(Request $request, $id)
    {
        $User = User::findOrFail($id);
        $User->delete();

        return response()->json(null, 204);

    }


    public function login(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }


    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response()->json($response, 200);
}


}
