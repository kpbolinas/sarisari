<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\OrderNotificationJob;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Checkout api
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $user = $request->user();
        $data = $request->all();
        $mailData = [
            'user' => [
                'email' => $user->email,
                'username' => $user->username,
                'name' => $user->first_name . ' ' . $user->last_name,
            ],
            'products' => $data,
        ];
        OrderNotificationJob::dispatch($mailData);

        return response()->respondSuccess([$mailData], 'Order received.');
    }
}
