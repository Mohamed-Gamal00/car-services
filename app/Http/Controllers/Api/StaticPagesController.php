<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\CommonQuestion;
use App\Models\Page;
use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
//    public function static_pages()
//    {
//
//        $About_app = Page::select('title', 'content')->where('id', '2')->first();
//        if ($About_app) {
//            $About_app->content = translateWithHTMLTags($About_app->content);
//            $About_app->title = translateWithHTMLTags($About_app->title);
//        }
//
//        $Terms_and_Conditions = Page::select('title', 'content')->where('id', '1')->first();
//        if ($Terms_and_Conditions) {
//            $Terms_and_Conditions->content = translateWithHTMLTags($Terms_and_Conditions->content);
//            $Terms_and_Conditions->title = translateWithHTMLTags($Terms_and_Conditions->title);
//
//        }
//
//        $data = [$About_app, $Terms_and_Conditions];
//        if ($data) {
//            return ApiResponse::sendResponse(200, 'data Retrieved Successfully', $data);
//        } else {
//            return ApiResponse::sendResponse(200, 'data not found');
//        }
//    }

    public function terms_and_Conditions()
    {
        $Terms_and_Conditions = Page::select('title', 'content')->where('id', '1')->first();
        if ($Terms_and_Conditions) {
            $Terms_and_Conditions->content = translateWithHTMLTags($Terms_and_Conditions->content);
            $Terms_and_Conditions->title = translateWithHTMLTags($Terms_and_Conditions->title);
 
        }

        $data = [$Terms_and_Conditions];
        if ($data) {
            return ApiResponse::sendResponse(200, 'data Retrieved Successfully', $data);
        } else {
            return ApiResponse::sendResponse(200, 'data not found');
        }
    }

    public function about_app()
    {
        $About_app = Page::select('title', 'content')->where('id', '2')->first();
        if ($About_app) {
            $About_app->content = translateWithHTMLTags($About_app->content);
            $About_app->title = translateWithHTMLTags($About_app->title);
        }

        $data = [$About_app];
        if ($data) {
            return ApiResponse::sendResponse(200, 'data Retrieved Successfully', $data);
        } else {
            return ApiResponse::sendResponse(200, 'data not found');
        }
    }

    public function common_questions()

    {
        $questions = CommonQuestion::select('title', 'description')->get();

        if ($questions->isEmpty()) {
            return ApiResponse::sendResponse(200, 'data not found');
        } else {
            return ApiResponse::sendResponse(200, 'data Retrieved Successfully', $questions);
        }
    }
}
