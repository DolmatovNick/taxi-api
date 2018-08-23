<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class InitController extends Controller
{

    public function index(JsonResponse $responce)
    {
        try {
            Artisan::call('migrate:refresh', [
                '--seed' => true
            ]);

            return $responce->setStatusCode(200)->setData([
                "message" => "Application setted up"
            ]);

        } catch (\Exception $ex){
            return $responce->setStatusCode(500)->setData([
                "message" => "Error in during application set up"
            ]);
        }
    }

}
