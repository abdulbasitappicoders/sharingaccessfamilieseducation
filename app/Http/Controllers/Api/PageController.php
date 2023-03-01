<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class PageController extends Controller
{
    public function index()
    {
        try {
            $page = Page::first();
            /*$page->termsCondition = strip_tags($page->termsCondition);
            $page->privacyPolicy = strip_tags($page->privacyPolicy);
            $page->help = strip_tags($page->help);
            $page->communityGroup = strip_tags($page->communityGroup);*/
            $page->termsCondition = html_entity_decode($page->termsCondition);
            $page->privacyPolicy = html_entity_decode($page->privacyPolicy);
            $page->help = html_entity_decode($page->help);
            $page->communityGroup = html_entity_decode($page->communityGroup);
            return apiresponse(true, 'data found',$page);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

}
