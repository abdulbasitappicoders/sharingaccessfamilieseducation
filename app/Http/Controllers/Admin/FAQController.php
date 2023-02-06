<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function faqCategories()
    {
        $faq_categories = FaqCategory::get();
        return view('faqManagement.faq_categories',compact('faq_categories'));
    }

    public function faqAnswers()
    {
        $faqs = Faq::get();
        return view('faqManagement.faq_ans',compact('faqs'));
    }
}
