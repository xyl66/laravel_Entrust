<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;
use MyViewComposer;
use Entrust;

class PermissionController extends Controller
{
    public function index()
    {
    	if(!Entrust::hasRole('developer')){
    		return redirect()->back()->withInput()->withErrors('没有权限');
    	}
    	$pers=Permission::get(); //不能修改admin信息
    	//$t=new MyViewComposer();
    	MyViewComposer::setnavbar([0,0,0,[0,1,0]]);
    	//Cache::put('navbar', [0,0,1,0], Carbon::now()->addSeconds(300));
    	return view('admin/permission',['usersdata'=>$pers]);
    }
    public function store(Request $request)
    {
    	if(!Entrust::hasRole('developer')){
    		return response()->json(['status'=>0,'msg'=>'抱歉您没有权限']);
    	}
        $owner = new Permission(); //创建权限实例
        $owner->name         = $request->input('form.name');
        $owner->display_name = $request->input('form.display_name'); // optional
        $owner->description  = $request->input('form.description'); // optional
        $owner->save(); //保存用户组导数据库
        $pers=Permission::get();                 //刷新数据
    	return response()->json(['status'=>1,'msg'=>'新增成功','data'=>$pers]);
    }
    public function update(Request $request)
    {
    	if(!Entrust::hasRole('developer')){
    		return response()->json(['status'=>0,'msg'=>'抱歉您没有权限']);
    	}
        $permissionid=$request->input('form.id');//获取提交的用户组
        $dbper=Permission::find($permissionid);
        //修改权限信息
        $dbper->name =$request->input('form.name');
        $dbper->display_name=$request->input('form.display_name');
        $dbper->description=$request->input('form.description');
        $dbper->save();
        $permissions=Permission::get();                 //刷新数据 不能修改admin信息
        return response()->json(['status' => 1,'msg' => '保存成功','data'=>$permissions]);
    }
    public function destroy($id)
    {   
    	if(!Entrust::hasRole('developer')){
    		return response()->json(['status'=>0,'msg'=>'抱歉您没有权限']);
    	}
    	$per=Permission::find($id);
        try {
            if($per){
                if($per->delete()){
                    $permissions=Permission::get(); //不能修改admin信息
                    return response()->json(['status'=>1,'msg'=>'删除成功','data'=>$permissions]);
                }else{
                    return response()->json(['status'=>0,'msg'=>'删除权限失败','data'=>$per]);
                }
            }
            return response()->json(['status'=>0,'msg'=>'未选择要删除的权限']);
        } catch (Exception $e) {
            return response()->json(['status'=>0,'msg'=>'系统错误联系管理员']);
        }
    }
}
