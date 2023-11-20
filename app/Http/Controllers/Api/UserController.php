<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        if (count($user) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!is_null($user)) {
            return response([
                'message' => 'User found, it is' . $user->username,
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 400);
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => 'required|max:60',
            'email' => 'required',
            'password' => 'required|min:8',
            'no_telp' => 'required|numeric|regex:/^08[0-9]{9,11}$/',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $image = $request->file('image');
        $imageData = base64_encode(file_get_contents($image->path()));
        $imageBase64 = "data:{$image->getClientMimeType()};base64,{$imageData}";
        $registrationData['image'] = $imageBase64;

    
        $user->name = $updateData['name'];
        $user->email = $updateData['email'];
        $user->password = $updateData['password'];
        $user->no_telp = $updateData['no_telp'];
        $user->image = $imageBase64;

        if ($user->save()) {
            return response([
                'message' => 'Update User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Update User Failed',
            'data' => null
        ], 400);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        if ($user->delete()) {
            return response([
                'message' => 'Delete User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Delete User Failed',
            'data' => null
        ], 400);
    }
}
