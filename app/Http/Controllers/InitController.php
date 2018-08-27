<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class InitController extends ApiController
{

    public function index(JsonResponse $response)
    {
        try {
            Artisan::call('migrate:refresh', [
                '--seed' => true
            ]);

            return $response->setStatusCode(200)->setData([
                "message" => "Application setted up"
            ]);

        } catch (\Exception $ex){
            return $response->setStatusCode(500)->setData([
                "message" => "Error in during application set up"
            ]);
        }
    }

}
