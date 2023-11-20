<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|max:8',
            'no_telp' => 'required|numeric|regex:/^08[0-9]{9,11}$/',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ]);
        if ($validate->fails()) {
            return response(['messaage' => $validate->errors()], 400);
        }

        $currentYear = date('y');
        $currentMonth = date('m');
        $latestUser = User::all()->last();

        if (is_null($latestUser)) {
            $newIndex = 1;
        } else {
            list($year, $month, $index) = explode('.', $latestUser->id);
            $newIndex = ($year == $currentYear && $month == $currentMonth) ? (int)$index + 1 : 1;
        }
        
        $newIndexFormatted = str_pad($newIndex, 2, '0', STR_PAD_LEFT); 
        
        $registrationData['id'] = $currentYear.".".$currentMonth.".".$newIndexFormatted;


        $image = $request->file('image');

        $registrationData['status'] = 0;
        $registrationData['password'] = bcrypt($request->password);

        $imageInfo = pathinfo($image->getClientOriginalName());
        $imageName = $imageInfo['filename'];
        $imageExtension = $imageInfo['extension'];
        $registrationData['image'] = $imageName .   '.' . $imageExtension;
        

        $user = User::create($registrationData);

        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response(['message' => 'Invalid Credential'], 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $token = $user->createToken('Authetication Token')->accessToken;

        return response([
            'message' => 'Autheticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }
}
