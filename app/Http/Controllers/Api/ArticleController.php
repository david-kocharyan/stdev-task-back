<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = !is_numeric($request->limit) ? 10 : $request->limit;
        $article = Article::with(["user", "category"])->paginate($limit);

        if ($article->isEmpty()) {
            return ResponseHelper::fail("Article Not Found", 422);
        }
        return ResponseHelper::success($article, true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticle(Request $request)
    {
        if (!is_numeric($request->article)) {
            return ResponseHelper::fail("Please Enter Correct Id!", ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }
        $article = Article::with(["user", "category"])->where('id', $request->article)->first();
        $resp = [
            'article' => $article,
        ];
        return ResponseHelper::success($resp, false);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = json_decode($request->getContent(), true);

        $validator = Validator::make($data,
            [
                'category_id' => 'required',
                'title' => 'required',
                'text' => 'required',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $article = new Article;
        $article->user_id = $user->id;
        $article->category_id = $data['category_id'];
        $article->title = $data['title'];
        $article->text = $data['text'];
        $article->save();

        return ResponseHelper::success(array(), false, "Article Created Successfully!");
    }

    public function articleByCategory(Request $request)
    {
        dd($request->category);
        if (!is_numeric($request->category)) {
            return ResponseHelper::fail("Please Enter Correct Category Id!", ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }
        $article = Article::with(["user", "category"])->where('category_id', $request->category)->get();
        $resp = [
            'article' => $article,
        ];
        return ResponseHelper::success($resp, false);
    }
}
