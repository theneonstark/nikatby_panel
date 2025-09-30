<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userbank;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController
{
    public function index(Request $post, $section = null)
    {
        // $user = User::find(session('loginid'));
        // $profile = \App\Models\Userkyc::where('user_id', session('loginid'))->first();
        // if ($profile && $profile->profile) {
        //     session(['profile' => $profile->profile]);
        // } else {
        //     session(["profile" => asset("") . "public/profiles/user.png"]);
        // }
        // try {
        //     session(["companyid" => $user->company_id]);
        //     session(["companyname" => $user->company->companyname]);
        // } catch (\Exception $e) {
        //     \DB::table('log_500')->insert([
        //         'line' => session("loginid"),
        //         'file' => $e->getFile(),
        //         'log'  => $e->getMessage(),
        //         'created_at' => date('Y-m-d H:i:s')
        //     ]);
        // }


        // session(['logo' => $user->company->logo]);
        // session(["kyc" => $user->kyc]);


        // $data['slides'] = \App\Models\PortalSetting::where('code', 'dashboardslides')->where('company_id', $user->company_id)->orderBy('id', 'desc')->get();



        // $data['videos'] = \App\Models\PortalSetting::where('code', 'dashboardvideos')->orderBy('id', 'desc')->get();

        // $data['mainwallet'] = $user->mainwallet;
        // $data['aepswallet'] = $user->aepswallet;
        // $notice = \DB::table('companydatas')->where('company_id', $user->company_id)->first(['notice', 'news', 'number', 'email']);

        // if ($notice) {
        //     $data['notice'] = $notice->notice;
        //     $data['news'] = $notice->news;
        //     $data['supportnumber'] = $notice->number;
        //     $data['supportemail'] = $notice->email;
        //     session(["news" => $notice->news]);
        // } else {
        //     $data['notice'] = "";
        //     $data['news']   = "";
        //     $data['supportnumber'] = "";
        //     $data['supportemail']  = "";
        //     session(["news" => ""]);
        // }

        // $pincheck = \DB::table('portal_settings')->where('code', 'pincheck')->first();

        // if ($pincheck) {
        //     if ($pincheck->value == 'yes') {
        //         if (!\MyHelper::can('pin_check')) {
        //             session(['pincheck' => $pincheck->value]);
        //         } else {
        //             session(["pincheck" => "no"]);
        //         }
        //     }
        // } else {
        //     session(["pincheck" => "no"]);
        // }

        // $data["virtual"] = \DB::table('virtual_accounts')->where('user_id', session('loginid'))->first();

        // if (!$data["virtual"]) {
        //     do {
        //         $account = "QUINT2119893" . rand(1111111, 9999999);
        //     } while (VirtualAccount::where("account", '=', $account)->first() instanceof VirtualAccount);
        // }

        // $data['report'] = \DB::table("reports")->where("user_id", session('loginid'))->whereDate('created_at', date("Y-m-d"))->where("status", "success")->where("rtype", "main")->orderBy('id', 'desc')->take(10)->get(['product', "amount", "txnid", "trans_type", "created_at"]);

        return Inertia::render('Dashboard');
    }

    public function onboarding(Request $post)
    {
        $data["user"]     =  User::find(session("loginid"));
        $data["userbank"] =  Userbank::where("user_id", session("loginid"))->where("type", "primary")->first();
        return Inertia::render('OnBoarding')->with($data);
    }
}
