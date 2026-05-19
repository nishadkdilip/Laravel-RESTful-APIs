<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginrRequests;
use App\Http\Requests\API\Auth\RegisterUserRequests;
use App\Http\Resources\UserResource;
use App\services\API\UserService;
use App\services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAPIController extends Controller
{
    protected $response;
    public function __construct()
    {
        $this->response = new ResponseService();
    }
    public function register(RegisterUserRequests $request): JsonResponse
    {
        $user =  UserService::registerUser($request->all());
        $token = $user->createToken("auth_token")->plainTextToken;
        return response()->json([
            "status" => true,
            "message" => "User Created Successfully!!",
            "data" => $user,
            "access_token" => "Bearer $token"
        ], 201);
    }

    public function login(LoginrRequests $request)
    {
        $credentials = $request->all();
        if (!Auth::attempt($credentials)) {
            return $this->response->error("Invalid Credentials", $credentials, 401);
        }

        $user = Auth::user();
        $token = $user->createToken("auth_token")->plainTextToken;
        return $this->response->success("User Logged in Successfully!!", [$user, "access_token" => "Bearer $token"]);
    }

    public function profile(Request $request)
    {
        $user = new UserResource($request->user());
        return $this->response->success("User profile fetched", $user);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return $this->response->success("Logout Success!!", "");
    }

    public function logoutAll(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return $this->response->success("Logged Out from all the devices successfully!!", "");
    }

    public function refresh(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $token = $user->createToken("auth_token")->plainTextToken;
        return $this->response->success("Token Refreshed Successfully!!",[$user,["access_token" => $token]]);
    }
}
