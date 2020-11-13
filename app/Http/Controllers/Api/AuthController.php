<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'nickname' => 'required|max:100',
                'email' => 'required|unique:users|max:150|email',
                'password' => 'required|max:25',
                'confirm_password' => 'required|same:password',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user = new User([
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->save();

        $user->createToken('Personal Access Token')->accessToken;

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $tokenRes = array(
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        );

        $resp = array(
            "user" => $user,
            "tokens" => $tokenRes
        );

        return ResponseHelper::success($resp, null, 'Successfully created user!');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data,
            [
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        if (!Auth::attempt($data)) {
            return ResponseHelper::fail("Unauthorized", ResponseHelper::UNAUTHORIZED);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $tokenRes = array(
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        );

        $resp = array(
            "user" => $user,
            "tokens" => $tokenRes
        );

        return ResponseHelper::success($resp, null, 'Successfully LogIn!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = Auth::guard('api')->user()->token();
        $token->revoke();
        return ResponseHelper::success(array(), null, 'Successfully logged out!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        $user = Auth::guard('api')->user();
        return ResponseHelper::success($user);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = json_decode($request->getContent(), true);

        $validator = Validator::make($data,
            [
                'nickname' => 'required',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user->nickname = $data['nickname'];
        $user->save();
        return ResponseHelper::success(array(), false, "Your Nickname Changed Successfully!");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = json_decode($request->getContent(), true);

        $validator = Validator::make($data,
            [
                'current_password' => 'required',
                'password' => 'required|same:password',
                'password_confirmation' => 'required|same:password',
            ]);

        if ($validator->fails()) {
            return ResponseHelper::fail($validator->errors()->first(), ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $current_password = $user->password;
        if (!Hash::check($data['current_password'], $current_password)) {
            return ResponseHelper::fail("Yor Current Password Is Wrong, Please Enter Valid Password!", ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        $user->password = Hash::make($data['password']);
        $user->save();
        return ResponseHelper::success(array(), false, "Your Password Changed Successfully!");
    }

    /**
     * @param Request $request
     */
    public function checkAuth(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = Auth::guard('api')->user();

        if ($user == null){
            return ResponseHelper::fail("User not Found", ResponseHelper::UNPROCESSABLE_ENTITY_EXPLAINED);
        }

        echo $user->token();

    }

}
