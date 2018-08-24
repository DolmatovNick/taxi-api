<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{

    protected function getCollectionResponse(ResourceCollection $collection)
    {
        if ( $collection->resource->total() == 0 ) {
            return static::gerResponseError('Not found', 404);
        }

        return $collection;
    }

    protected static function gerResponseError($message, $code)
    {
        return Response::json([
            'error' => [
                'message' => $message
            ]
        ], $code)->withException(new \Exception());
    }
}
