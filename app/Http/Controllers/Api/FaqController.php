<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqCategoryResource;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        try {
            $faqs = FaqCategory::with('faqs')->get();
            if ($faqs->count() > 0) {
                $faqs = FaqCategoryResource::collection($faqs);
            }
            return apiresponse(true, __('Faqs loaded successfully'), ['data' => $faqs]);
        } catch (\Exception $exception) {
            return apiresponse(false, $exception->getMessage());
        }
    }
}
