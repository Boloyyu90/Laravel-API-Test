<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user'])->get();

        if (count($subscriptions) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $subscriptions
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_user' => 'required',
            'category' => 'required|in:Basic,Standard,Premium',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $user = User::find($storeData['id_user']);
        if (!$user) {
            return response(['message' => 'User not Found'], 400);
        }

        if ($user->status == 1) {
            return response(['message' => 'Cannot create Subscription'], 400);
        }

        $subscriptions = new Subscription();
        
        switch ($storeData['category']) {
            case 'Basic':
                $subscriptions->price = 50000;
                break;
            case 'Premium':
                $subscriptions->price = 100000;
                break;
            default:
                $subscriptions->price = 150000;
                break;
        }

        $storeData['transaction_date'] = now();

        $subscriptions->fill($storeData);
        $subscriptions->save();

        $user->status = 1;
        $user->save();

        return response([
            'message' => $user->name . ' accessed ' . $subscriptions->category . ' at ' . $subscriptions['transaction_date'] . '.',
            'data' => $subscriptions
        ], 200);
    }

    public function show(string $id)
    {
        $subscription = Subscription::find($id);

        if (!is_null($subscription)) {
            return response([
                'message' => 'Subscription found',
                'data' => $subscription
            ], 200);
        }

        return response([
            'message' => 'Subscription Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, string $id)
    {
        $updateData = $request->all();
        $subscriptions = Subscription::find($id);
        if (is_null($subscriptions)) {
            return response([
                'message' => 'Subscription Not Found',
                'data' => null
            ], 404);
        }

        $validate = Validator::make($updateData, [
            'id_user' => 'required',
            'category' => 'required|in:Basic,Standard,Premium',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $user = User::find($updateData['id_user']);
        if (!$user) {
            return response(['message' => 'User Not Found'], 400);
        }

        if ($user->status == 0) {
            return response(['message' => 'Cannot update Subscription'], 400);
        }
        $subscriptions->id_user = $updateData['id_user'];
        $subscriptions->category = $updateData['category'];

        if ($updateData['category'] == 'Basic') {
            $subscriptions->price = 50000;
        } else if ($updateData['category'] == 'Premium') {
            $subscriptions->price = 100000;
        } else {
            $subscriptions->price = 150000;
        }

        if ($subscriptions->save()) {
            return response([
                'message' => 'Update Subscriptions Success',
                'data' => $subscriptions
            ], 200);
        }

        return response([
            'message' => 'Update Subscriptions Failed',
            'data' => null
        ], 400);
    }

    public function destroy(string $id)
    {
        $subscriptions = Subscription::find($id);

        if (is_null($subscriptions)) {
            return response([
                'message' => 'Subscriptions Not Found',
                'data' => null
            ], 404);
        }

        if ($subscriptions->delete()) {
            return response([
                'message' => 'Delete Subscriptions Success',
                'data' => $subscriptions
            ], 200);
        }

        return response([
            'message' => 'Delete Subscriptions Failed',
            'data' => null
        ], 400);
    }
}
