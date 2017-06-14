<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\User;
use App\Permission;
use Illuminate\Support\Facades\Auth;
use Entrust;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function test(){
        /*$admin = Role::where('name', 'admin')->first();
        $user = User::where('name', '=', 'uer-Test')->first();

        // role attach alias
        $user->attachRole($admin); // parameter can be an Role object, array, or id*/

        /*$createPost = new Permission();
        $createPost->name         = 'create-post';
        $createPost->display_name = 'Create Posts'; // optional
        // Allow a user to...
        $createPost->description  = 'create new blog posts'; // optional
        $createPost->save();

        $editUser = new Permission();
        $editUser->name         = 'edit-user';
        $editUser->display_name = 'Edit Users'; // optional
        // Allow a user to...
        $editUser->description  = 'edit existing users'; // optional
        $editUser->save();
        $admin = Role::where('name', 'admin')->first();
        $owner = Role::where('name', 'owner')->first();
        $admin->attachPermission($createPost);
        // equivalent to $admin->perms()->sync(array($createPost->id));

        $owner->attachPermissions(array($createPost, $editUser));*/
        // equivalent to $owner->perms()->sync(array($createPost->id, $editUser->id));
        $user = User::where('name', '=', 'uer-Test')->first();
        $t=$user->hasRole('owner')?'true':'false';   // false
        $t1=Entrust::hasRole('admin')?'true':'false';   // true
        $t2=Auth::user()->can('edit-user')?'true':'false';;   // false
        $t3=Auth::user()->can('create-post')?'true':'false';; // true
        return view('test',['t' => $t,'t1' => $t1,'t2' => $t2,'t3' => $t3]);
    }
}
