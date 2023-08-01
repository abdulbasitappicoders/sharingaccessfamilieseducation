<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Page, ContactUs, User, RidePayment, WebContactUs};
use Illuminate\Support\Facades\Crypt;

class PageController extends Controller
{

    public function verifyEmail($id,$code){
        $user = User::find($id);
        if($user){
            if($user->confirmation_code == $code){
                $user->confirmation_code = null;
                $user->is_verified = 1;
                if($user->save()){
                    return redirect('/');
                }
            }else{
                return "Link expired";
            }
        }else{
            return "User not found";
        }
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
            return back()->with('message','Community Group updated Successfully');
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
            return back()->with('message','Terms And Condition updated Successfully');
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
            return back()->with('message','Term And Services Updated Successfully');
        }
    }

    //Queries Module
    public function queries(){
        $queries = ContactUs::with('user')->orderBy('id','DESC')->get();
//        $queries = ContactUs::with('user')->orderBy('id','DESC')->simplePaginate(10);
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

    public function payments(){
//        $payments = RidePayment::with('ride','driver','rider','payment_method')->orderBy('id', 'DESC')->simplePaginate(10);
        $payments = RidePayment::with('ride','driver','rider','payment_method')->orderBy('id', 'DESC')->get();
        return view('payment.index',compact('payments'));
    }

    public function webQueries()
    {
        $queries = WebContactUs::orderBy('id', 'DESC')->simplePaginate(10);
        return view('queries.web-queries', compact('queries'));
    }

    public function deleteWebInquiry(WebContactUs $contactUs)
    {
        try {
//            dd($contactUs);
            $contactUs->delete();
            return redirect()->back()->with('success', "Inquiry has been deleted successfully.");
        } catch (Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function deleteInquiry($id)
    {
        try {
//            dd($id)
            $contactUs = ContactUs::find($id);
            $contactUs->delete();
            return redirect()->back()->with('success', "Query has been deleted successfully.");
        } catch (Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    // public function update_privacyAndPolicy(Request $request){
    //     $privacyPolicy = Page::find($request->id);
    //     $privacyPolicy->privacyPolicy = $request->privacyPolicy;
    //     if($privacyPolicy->save()){
    //         return back()->with('message','Terms And Condition updated');
    //     }
    // }

    public function readInquiry($id){
//        dd($id);
        $query = ContactUs::find($id);
        if ($query && $query->is_read_query == null){
            $query->is_read_query = 1;
            $query->save();
            return 'success';
        }
//        dd($query);
    }


    public function queryNotification(){
        $queries = ContactUs::orderBy('id', 'DESC')->get();
        return view('queries.web_queries_notification',compact('queries'));
    }
}
