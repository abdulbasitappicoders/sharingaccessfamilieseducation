<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
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

}
