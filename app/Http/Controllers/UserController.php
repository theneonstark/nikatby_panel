<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController
{
    public function loginpage(Request $get)
    {
        $data['company'] = \App\Models\Company::where('website', $_SERVER['HTTP_HOST'])->first();
        if ($this->env_mode() == "server") {
            if($_SERVER['HTTP_HOST'] == "e-banker.in"){
                $data['company'] = \App\Models\Company::where('website', "retail.e-banker.in")->first();
                // return view('welcome')->with($data);
                // return Inertia::render('Auth/login');
            }
        }   
        
        $data['company'] = \App\Models\Company::where('website', $_SERVER['HTTP_HOST'])->first();

        // if($data['company']){
        //     $data['slides'] = \App\Models\PortalSetting::where('code', 'slides')->where('company_id', $data['company']->id)->orderBy('id', 'desc')->get();
        // }else{
        //     dd($data['company']);
        //     abort(404);
        // }
        // $data['state'] = Circle::all();
        // $data['roles'] = Role::whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer'])->get();
        
        return Inertia::render('Auth/login');
    }
}
