<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fio' => $this->fio,
        ];
    }

    public function withResponse($request, $response)
    {
        dump($request);
        dump($response);
        dd(123);

        $response->setStatusCode(404);
    }
}
