<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Jobs\OrderNotificationJob;

class OrderController extends Controller
{
    /**
     * Checkout api
     *
     * @param \App\Http\Requests\OrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(OrderRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $mailData = [
            'user' => [
                'email' => $user->email,
                'username' => $user->username,
                'name' => $user->first_name . ' ' . $user->last_name,
            ],
            'products' => $data,
        ];
        OrderNotificationJob::dispatch($mailData);

        return response()->respondSuccess([], 'Order received.');
    }
}
