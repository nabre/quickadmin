<?php
namespace Nabre\Quickadmin\Http\Controllers\User;

use Nabre\Quickadmin\Http\Controllers\Controller;
<<<<<<< HEAD

class ProfileController extends Controller
{
    function index(){
        return view('nabre-quickadmin::quick.user',['CONTENT'=>'Profile']);
    }
    /*
    #Display the user's profile form.

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    #Update the user's profile information.

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }*/
=======
use Nabre\Quickadmin\Forms\User\ProfileForm as Form;
class ProfileController extends Controller{
    function index(){
        $CONTENT = Form::public();
        return view('nabre-quickadmin::quick.user', get_defined_vars());
    }
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
}
