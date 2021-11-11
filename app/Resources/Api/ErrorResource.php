<?php

namespace App\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $error = [
            'status' => 'error',
            'error' => [
                'title' => method_exists($this->resource, 'getTitle') ? $this->resource->getTitle() : 'Internal Server Error',
                'message' => method_exists($this->resource, 'getMessage') ? $this->resource->getMessage() : 'The server encountered an internal error.',
            ]
        ];

        if (config('app.debug', false)) {
            $error['error']['details'] = [
                'exception' => get_class($this),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => collect($this->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ];
        }

        return $error;
    }
}
