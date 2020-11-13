<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments(Request $request)
    {
        if (!is_numeric($request->article)) {
            return ResponseHelper::fail("Please Enter Correct Id!", ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }
        $comment = Comment::with(["user"])->where('article_id', $request->article)->latest()->take(5)->get();
        $resp = [
            'comment' => $comment,
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
                'article_id' => 'required',
                'text' => 'required',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $comment = new Comment;
        $comment->user_id = $user->id;
        $comment->article_id = $data['article_id'];
        $comment->text = $data['text'];
        $comment->save();

        return ResponseHelper::success(array(), false, "Comment Save Successfully!");
    }
}
