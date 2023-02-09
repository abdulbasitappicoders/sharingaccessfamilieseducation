<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatList;
use App\Models\ChatListMessage;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function React\Promise\all;

class FAQController extends Controller
{
    public function faqCategories()
    {
        $faq_categories = FaqCategory::orderBy('id','DESC')->get();
        return view('faqManagement.faq_categories',compact('faq_categories'));
    }

    public function insertFaqCategories(Request $request)
    {
        try {
            $request->validate([
                "name"          => "required",
            ]);

            $faqCategory = new FaqCategory();
            $faqCategory->name = $request->name;

            if ($faqCategory->save()) {
                return back()->with('success', 'Faq Category created');
            }
            return back()->with('error', 'Faq Category not created');
        } catch (\Exception $exception) {
            return redirect()->route('admin.faq_categories')->with('error', $exception->getMessage());
        }
    }

    public function editFaqCategories(Request $request)
    {
        try {
            $faqCategory = FaqCategory::find($request->id);
            return response()->json($faqCategory);
        } catch (\Exception $exception) {
            return redirect()->route('admin.faq_categories')->with('error', $exception->getMessage());
        }
    }

    public function updateFaqCategories(Request $request)
    {

        try {
            $faqCategory = FaqCategory::find($request->id);
            $faqCategory->name = $request->name;

            if ($faqCategory->save()) {
                return back()->with('success', 'Faq Category updated');
            }
            return back()->with('error', 'Faq Category not updated');
        } catch (\Exception $exception) {
            return redirect()->route('admin.faq_categories')->with('error', $exception->getMessage());
        }
    }

    public function deleteFaqCategories($id)
    {
        try {
            $faqCategory = FaqCategory::find($id);
            $res = $faqCategory->delete();

            if ($res) {
                return back()->with('success', 'Faq Category deleted');
            }
            return back()->with('error', 'Faq Category not deleted');
        } catch (\Exception $exception) {
            return redirect()->route('admin.faq_categories')->with('error', $exception->getMessage());
        }
    }

    public function faqAnswers()
    {
        $faqs = Faq::get();
        $faq_categories = FaqCategory::orderBy('id','DESC')->get();
        return view('faqManagement.faq_ans',compact('faqs','faq_categories'));
    }

    public function insertFaqAnswers(Request $request)
    {
        try {
            $request->validate([
                "faq_category_id"   => "required",
                "question"          => "required",
                "answer"            => "required",
            ]);

            $faq= new Faq();
            $faq->faq_category_id = $request->faq_category_id;
            $faq->question = $request->question;
            $faq->answer = $request->answer;

            if ($faq->save()) {
                return back()->with('success', 'Faq Ans created');
            }
            return back()->with('error', 'Faq Ans not created');
        } catch (\Exception $exception) {
            return redirect()->route('admin.faq_answers')->with('error', $exception->getMessage());
        }
    }

    public function editFaqAnswers(Request $request)
    {
        try {
            $faqCategory = Faq::find((int)$request->id);
            $faq_categories = FaqCategory::orderBy('id','DESC')->get();
            $options ='';
            foreach ($faq_categories as $faq)
            {
                $select = ($faq->id == $faqCategory->faq_category_id)?"selected":'';
                $options .= "<option value=\"$faq->id\" $select >$faq->name</option>";
            }
            return response()->json(['options'=>$options,'faqs'=>$faqCategory]);
        } catch (\Exception $exception) {
            return redirect()->route('admin.faq_answers')->with('error', $exception->getMessage());
        }
    }

    public function updateFaqAnswers(Request $request)
    {
        try {
            $faq = Faq::find((int)$request->id);
            $faq->faq_category_id = $request->faq_category_id;
            $faq->question = $request->questions;
            $faq->answer = $request->answers;

            if ($faq->save()) {
                return back()->with('success', 'Faq Answer updated');
            }
            return back()->with('error', 'Faq Answer not updated');
        } catch (\Exception $exception) {
            return redirect()->route('admin.faq_categories')->with('error', $exception->getMessage());
        }
    }

    public function deleteFaqAnswers($id)
    {
        try {
            $faq = Faq::find($id);
            $res = $faq->delete();

            if ($res) {
                return back()->with('success', 'Faq Answer deleted');
            }
            return back()->with('error', 'Faq Answer not deleted');
        } catch (\Exception $exception) {
            return redirect()->route('admin.faq_answers')->with('error', $exception->getMessage());
        }
    }

    public function getStaff()
    {
        $staffs = User::where('role', 'staff')->orderBy('id', 'DESC')->get();
        $faq_categories = FaqCategory::orderBy('id', 'DESC')->get();
        return view('faqManagement.staff', compact('staffs', 'faq_categories'));
    }

    public function insertStaff(Request $request)
    {
        try {
            $request->validate([
                "first_name"             => "required",
                "last_name"              => "required",
                "email"                  => "required",
                "gender"                 => "required",
                "support_category_id"                 => "required",
                "password"               => "required",
                "confirm_password"       => "required|same:password",
            ]);

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->username = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->support_category_id = $request->support_category_id;
            $user->vehicle_type = 'car';
            $user->role = 'staff';
            $user->password = Hash::make($request->password);
            $user->status = 1;

            if ($user->save()) {
                return back()->with('success', 'Staff created');
            }
            return back()->with('error', 'Staff not created');
        } catch (\Exception $exception) {
            return redirect()->route('admin.staff')->with('error', $exception->getMessage());
        }
    }

    public function editStaff(Request $request)
    {
        try {
            $user_staff = User::find((int)$request->id);
            $faq_categories = FaqCategory::orderBy('id','DESC')->get();
            $options ='';
            foreach ($faq_categories as $faq)
            {
                $select = ($faq->id == $user_staff->support_category_id)?"selected":'';
                $options .= "<option value=\"$faq->id\" $select >$faq->name</option>";
            }
            return response()->json(['user_staff'=>$user_staff,'options'=>$options]);
        } catch (\Exception $exception) {
            return redirect()->route('admin.staff')->with('error', $exception->getMessage());
        }
    }

    public function updateStaff(Request $request)
    {
        try {
            $request->validate([
                "first_name" => "required",
                "last_name" => "required",
                "gender" => "required",
                "support_category_id" => "required",
            ]);

            $user = User::find((int)$request->id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->username = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->gender = $request->staffgender;
            $user->support_category_id = $request->support_category_id;

            if ($user->save()) {
                return back()->with('success', 'Staff Updated Successfully');
            }
            return back()->with('error', 'Staff not updated created');
        } catch (\Exception $exception) {
            return redirect()->route('admin.staff')->with('error', $exception->getMessage());
        }
    }

    public function deleteStaff($id)
    {
        try {
            $user = User::find($id);
            $res = $user->delete();

            if ($res) {
                return back()->with('success', 'Staff deleted');
            }
            return back()->with('error', 'Staff not deleted');
        } catch (\Exception $exception) {
            return redirect()->route('admin.staff')->with('error', $exception->getMessage());
        }
    }

    public function getFaqQuries(Request $request)
    {
        $faq_categories = FaqCategory::orderBy('id', 'DESC')->get();
        $chatlists = ChatList::where("faq_category_id", $request->category_id)->get();

        return view('faqManagement.faq_queries', compact('faq_categories', 'chatlists'));
    }

   public function faqQurieChat($id)
   {
       $id = decrypt($id);
       $chat_list_messages = SupportMessage::where('chat_list_id',$id)->get();
       return view('faqManagement.faq_querie_chat',compact('chat_list_messages'));
   }
}
