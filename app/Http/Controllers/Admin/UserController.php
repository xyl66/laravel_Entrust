<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use MyViewComposer;
use Entrust;

class UserController extends Controller
{
    public function index()
    {
        if(!Entrust::ability(array('admin'), array('user-edit','user-delete','user-list'))){
            return redirect()->back()->withInput()->withErrors('没有权限');
        }
    	$users=User::where('email','!=', 'admin@admin.com')->get(); //不能修改admin信息
    	foreach ($users as $user) {
    		$user->roles;
    	}
    	//$t=new MyViewComposer();
    	MyViewComposer::setnavbar([0,0,0,[0,0,1]]);
    	//Cache::put('navbar', [0,0,1,0], Carbon::now()->addSeconds(300));
    	return view('admin/user',['usersdata'=>$users,'roles'=>Role::all()]);
    }
    public function update(Request $request)
    {
        if(!Entrust::ability(array('admin'), array('user-edit'))){
            return response()->json(['status'=>0,'msg'=>'抱歉您没有权限']);
        }
        $userid=$request->input('form.id');//获取提交的用户组
        $dbuser=User::find($userid);
        //修改用户组信息
        $dbuser->name =$request->input('form.name');
        $dbuser->save();
        //修改权限
        $dbuser->roles()->sync($request->get('checkedCities'));
        $users=User::where('email','!=', 'admin@admin.com')->get();                 //刷新数据 不能修改admin信息
        foreach ($users as $user) {
            $user->roles;
        };
        return response()->json(['status' => 1,'msg' => '保存成功','data'=>$users]);
    }
    public function destroy(User $user)
    {   
        if(!Entrust::ability(array('admin'), array('user-delete'))){
            return response()->json(['status'=>0,'msg'=>'抱歉您没有权限']);
        }
        try {
            if($user){
                if($user->delete()){
                    $users=User::where('email','!=', 'admin@admin.com')->get();                 //刷新数据
                    foreach ($users as $a) {
                        $a->permissions;
                    };
                    return response()->json(['status'=>1,'msg'=>'删除成功','data'=>$users]);
                }else{
                    return response()->json(['status'=>0,'msg'=>'删除用户失败']);
                }
            }
            return response()->json(['status'=>0,'msg'=>'未选择要删除的用户']);
        } catch (Exception $e) {
            return response()->json(['status'=>0,'msg'=>'系统错误联系管理员']);
        }
    }
}
