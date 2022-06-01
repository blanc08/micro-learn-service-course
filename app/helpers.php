<?php

use Illuminate\Support\Facades\Http;


function postOrder($params)
{

    $url = env('SERVICE_PAYMENT_URL') . 'api/orders';

    try {
        $response = Http::post($url, $params);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'Service Payment unavailable',
        ];
    }
}

function getUser($user_id)
{
    $url = env('SERVICE_USER_URL') . '/users/' . $user_id;

    try {
        $response = Http::timeout(10)->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();

        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'Service unavailable',
        ];
    }
}

function getUserByIds($userIds = [])
{
    $url = env('SERVICE_USER_URL') . '/users/';

    try {
        if (count($userIds) === 0) {

            return [
                'status' => 'success',
                'http_code' => 200,
                'data' => [],
            ];
        }

        $response = Http::timeout(10)->get($url, [
            'user_ids[]' => $userIds,
        ]);

        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();

        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'Service unavailable',
        ];
    }
}
