<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use App\User;

use Intervention\Image\ImageManagerStatic as Image;


use App\Http\Requests\StoreInvitationRequest;
use App\Invitation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;

class InvitationsController extends Controller
{

    use RegistersUsers;
    
    public function index()
    {
        $invitations = Invitation::where('registered_at', null)->orderBy('created_at', 'desc')->get();
        return view('invitations.index', compact('invitations'));
    }

    public function store(StoreInvitationRequest $request)
    {

        $invitation = new Invitation($request->all());
        $invitation->generateInvitationToken();

        $send = $invitation->save();

        if ($send > 0) {

              $email = $request->input('email');

              $token = Invitation::where('email', $email)->value('invitation_token');
              $content = url('/').'/'."register?invitation_token=".$token;

              $data = array('links'=>$content);

              Mail::send('mail', $data, function($message) use($email) {

                 $message->to($email)->subject('User Invites Email');
                 $message->from('admin@gmail.com','Admin');
              });
        }

        return redirect()->route('requestInvitation')
            ->with('success', 'Invitation to register successfully requested. Please wait for registration link.');
    }

    public function requestInvitation() {
        return view('auth.request');
    }

    public function registered(Request $request, $user){
        $invitation = Invitation::where('email', $user->email)->firstOrFail();
        $invitation->registered_at = $user->created_at;
        $invitation->save();
    }

    public function profile(){
        $id = Auth::user()->id;
        $users = User::where('id', $id)->get();
        return view('profile.index', compact('users'));
    }

    public function profileUpdate(Request $request){
        $catid = Auth::user()->id;

        //Image
        $filename = "test.jpg";
        $avatar = $request->file('profileImg');

      if ($avatar !== null) {
        $filename = time() . '.' . $avatar->getClientOriginalExtension();
        Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/admin/' . $filename) );
       }

        $email = $request->input('email');
        $name = $request->input('name');
        $username = $request->input('username');

        //$request->input('email');
        $query = User::where('id', $catid)
        ->update([
           'email' => $email,
           'name' => $name,
           'username' => $username,
           'profileImg' => $filename
        ]);

        return redirect()->route('profile')->with('success', 'Updated'); 
    }


}
