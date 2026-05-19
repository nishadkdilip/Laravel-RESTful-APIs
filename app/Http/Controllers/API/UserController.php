<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $response;
    public function __construct()
    {
        $this->response = new ResponseService();
    }
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? "15";
        $user = User::latest()->paginate($perPage);
        $data = new UserCollection($user);
        return $this->response->success("Users Retrieved Successfully!!", $user);
    }

    public function show(User $user)
    {
        $data = new UserResource($user);
        return $this->response->success("User Retrieved Succcessfully!!", $data);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        if ($request->user()->id != $user->id) {
            return $this->response->error("You're not authorized to update another user's data", $request->All(), 403);
        }
        $data = $request->validated();
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $updateData = [];
        foreach ($data as $key => $value) {
            if ($value !== null) {
                $updateData[$key] = $value;
            }
        }

        $user->update($updateData);
        $updatedUser = new UserResource($user->fresh());
        return $this->response->success("Profile Updated Successfully!!", $updatedUser);
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id != $user->id) {
            return $this->response->error("You're not authorized to delete this user's data", $request->All(), 403);
        }

        $user->tokens()->delete();
        $user->delete();

        return $this->response->success("User Deleted Successfully!!", "");
    }
    public function deletedUsers(Request $request)
    {
        $perPage = $request->per_page ?? "15";
        $deletedUsers =  User::latest()->onlyTrashed()->paginate($perPage);
        $data = new UserResource($deletedUsers);
        return $this->response->success("Deleted Users List!!", $data);
    }

    public function restoreUser(Request $request)
    {
        $id = $request->id ?? "";
        if ($request->user()->user_type != "admin") {
            return $this->response->error("Access Denied!! You Do Not have rights to restore the user.", $request->all());
        }
        $user = User::withTrashed()->find($id)->restore();
        return $this->response->success("User restored successfully!!", $user);
    }
}
