<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
//use App\Contracts\ComposerContract;
//use App\Facades\MyViewComposer; //使用全名
use MyViewComposer;//使用别名
use Entrust;

class RoleController extends Controller
{
	//依赖注入
   /* public function __construct(ComposerContract $test){
        $this->composer = $test;
    }*/

    public function index()
    {
        if(!Entrust::ability(array('admin'), array('role-create', 'role-edit','role-delete'))){
            return redirect()->back()->withInput()->withErrors('没有权限');
        }
    	$users=Role::get();
    	foreach ($users as $user) {
    		$user->permissions;
    	}
    	// $this->composer->setnavbar([0,0,0,[1,0,0]]);
         MyViewComposer::setnavbar([0,0,0,[1,0,0]]);
    	//Cache::put('navbar', [0,0,1,0], Carbon::now()->addSeconds(300));
    	return view('admin/role',['roledata'=>$users,'permissions'=>Permission::all()]);
    }
    public function store(Request $request)
    {
        if(!Entrust::ability(array('admin'), array('role-create'))){
            return response()->json(['status'=>0,'msg'=>'抱歉您没有权限']);
        }
        if(Role::where('name','=',$request->input('form.name'))->first()){
            return response()->json(['status'=>0,'msg'=>'角色名称已存在']);
        };
        $owner = new Role(); //创建用户组实例
        $owner->name         = $request->input('form.name');
        $owner->display_name = $request->input('form.display_name'); // optional
        $owner->description  = $request->input('form.description'); // optional
        $owner->save(); //保存用户组导数据库
        $owner->perms()->sync($request->get('checkedCities'));//保存权限
        $users=Role::get();                 //刷新数据
        foreach ($users as $user) {
            $user->permissions;
        };
    	return response()->json(['status'=>1,'msg'=>'新增成功','data'=>$users]);
    }
    public function update(Request $request)
    {
        if(!Entrust::ability(array('admin'), array('role-edit'))){
            return response()->json(['status'=>0,'msg'=>'抱歉您没有权限']);
        }
    	$roleid=$request->input('form.id');//获取提交的用户组
    	$dbrole=Role::find($roleid);
    	//修改用户组信息
    	$dbrole->name =$request->input('form.name');
		$dbrole->display_name =$request->input('form.display_name');
		$dbrole->description =$request->input('form.description');
		$dbrole->save();
		//修改权限
		$dbrole->perms()->sync($request->get('checkedCities'));
        $users=Role::get();                 //刷新数据
        foreach ($users as $user) {
            $user->permissions;
        };
		return response()->json(['status' => 1,'msg' => '保存成功','data'=>$users]);
    }
    public function destroy(Role $role)
    {   
        if(!Entrust::can('role-delete')){
            return response()->json(['status'=>0,'msg'=>'抱歉您没有权限']);
        }
        if($role){
            if($role->name==='admin'){
                return response()->json(['status'=>0,'msg'=>'抱歉该角色无法删除']);
            }
            if($role->delete()){
                $roles=Role::get();                 //刷新数据
                foreach ($roles as $a) {
                    $a->permissions;
                };
                return response()->json(['status'=>1,'msg'=>'删除成功','data'=>$roles]);
            }else{
                return response()->json(['status'=>0,'msg'=>'删除角色失败']);
            }
        }
        return response()->json(['status'=>0,'msg'=>'未选择要删除的角色']);
    }
}
