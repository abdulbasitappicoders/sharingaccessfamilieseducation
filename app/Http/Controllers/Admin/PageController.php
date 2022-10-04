<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Page,ContactUs,User};
use Illuminate\Support\Facades\Crypt;

class PageController extends Controller
{

    public function verifyEmail(){
        return view('mail.verification');
    }

    //Community Group Module
    public function community_group(){
        $community = Page::first();
        return view('community.edit',compact('community'));
    }

    public function update_community_group(Request $request){
        $community = Page::find($request->id);
        $community->communityGroup = $request->communityGroup;
        if($community->save()){
            return back()->with('message','Community Group updated');
        }
    }

    //Help Module
    public function help(){
        $help = Page::first();
        return view('help.edit',compact('help'));
    }

    public function update_help(Request $request){
        $help = Page::find($request->id);
        $help->help = $request->help;
        if($help->save()){
            return back()->with('message','Help updated');
        }
    }

    //termsCondition Module
    public function termsCondition(){
        $termsCondition = Page::first();
        return view('TermsAndService.edit',compact('termsCondition'));
    }

    public function update_termsCondition(Request $request){
        $termsCondition = Page::find($request->id);
        $termsCondition->termsCondition = $request->termsCondition;
        if($termsCondition->save()){
            return back()->with('message','Terms And Condition updated');
        }
    }

    //privacyAndPolicy Module
    public function privacyAndPolicy(){
        $privacyPolicy = Page::first();
        return view('privacyAndPolicy.edit',compact('privacyPolicy'));
    }

    public function update_privacyAndPolicy(Request $request){
        $privacyPolicy = Page::find($request->id);
        $privacyPolicy->privacyPolicy = $request->privacyPolicy;
        if($privacyPolicy->save()){
            return back()->with('message','Terms And Condition updated');
        }
    }

    //Queries Module
    public function queries(){
        $queries = ContactUs::with('user')->orderBy('id','DESC')->simplePaginate(10);
        return view('queries.index',compact('queries'));
    }

    public function query_user($id){
        $user = User::find(Crypt::decryptString($id));
        if($user){
            return view('queries.user',compact('user'));;
        }else{
            return back()->with('message','User not found');
        }
        
    }

    // public function update_privacyAndPolicy(Request $request){
    //     $privacyPolicy = Page::find($request->id);
    //     $privacyPolicy->privacyPolicy = $request->privacyPolicy;
    //     if($privacyPolicy->save()){
    //         return back()->with('message','Terms And Condition updated');
    //     }
    // }
}
