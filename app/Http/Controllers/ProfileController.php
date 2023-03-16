<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */

     function __construct()
     {
         $this->middleware('permission:myprofile', ['only' => ['myProfile','updateMyProfile']]);

     }
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function myProfile(){

        $Profile = User::where('id',  Auth::user()->id)->first();
        if (!$Profile)
        {
            return redirect()->back()->with('error', 'Your are not allowed to see.');
        }
        else {

            return view('admin.profile.edit-profile', compact('Profile'));
        }
    }
    public function updateMyProfile(Request $request){
        $request->validate([

            'email' => 'email',


           ]);

           $user = User::find($request->id);
           $user->name = $request->name;
           $user->email = $request->email;
           if($request->password){
            $request->validate([

                'password' => 'required|confirmed|min:8',

               ]);
            $user->password = Hash::make($request->password);
           }

           if ($request->hasFile('image')) {
            $request->validate([

                'image' => 'mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
             ]);
             if(file_exists(public_path('uploads/'.$user->image)) && !is_null($user->image)){
                unlink(public_path('uploads/'.$user->image));
            }
            $file= $request->file('image');
            $user->image = imageUp($file);

        }
        $user->save();


        return redirect()->route('myprofile')->with('success','Update User successfully');



    }
}
