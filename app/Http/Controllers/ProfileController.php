<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;

class ProfileController extends Controller
{

    use RegistersUsers;
    
    public function index()
    {

        $id = Auth::user()->id;
        $users = User::where('id', $id)->get();
        return view('profileuser.index', compact('users'));
    }


    public function UserProfileup(Request $request){
         $catid = Auth::user()->id;
        //Image
        $filename = "test.jpg";
        $avatar = $request->file('profileImg');

        if ($avatar !== null) {
           $filename = time() . '.' . $avatar->getClientOriginalExtension();
           Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/users/' . $filename) );
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

        return redirect()->route('profileuser')->with('success', 'Updated'); 
    }


}
