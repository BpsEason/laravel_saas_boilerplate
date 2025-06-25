<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * Customize the outgoing response for a resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\JsonResponse  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        # For JSend compliance, we manually wrap the data in a 'data' key
        # This is a simple example, more complex JSend implementations might be needed.
        $response->setData([
            'status' => 'success',
            'data' => $response->getData(true), # Get original data as array
            'message' => 'Operation successful', # Generic success message
        ]);
    }
}
