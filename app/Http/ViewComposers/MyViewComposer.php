<?php namespace App\Http\ViewComposers;
use Illuminate\Contracts\View\View;
use App\Contracts\ComposerContract;
use Cache;
use Carbon\Carbon;
class MyViewComposer implements ComposerContract
{
    public function compose(View $view)
    { 
        $view->with('sitename',Cache::get('navbar',[1,0,0,0]));
    }
    public function setnavbar($data){
    	Cache::put('navbar', $data, Carbon::now()->addSeconds(300));
    }
}