<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $transformater = null;

    public function respond($data, $statusCode = 200, $headers = [])
    {
        return response()->json($data, $statusCode, $headers);
    }

    protected function respondCreated($data)
    {
        return $this->respond($data, 201);
    }

    protected function respondWithTransformer($data, $statusCode = 200, $headers = [])
    {
        if ($this->transformer !== null) {
            if ($data instanceof Collection) {
                $data = $this->transformater->collection($data->toArray());
            } else {
                $data = $this->transformer->item($data);
            }
        }

        return $this->respond($data, $statusCode, $headers);
    }

    protected function respondNoContent($data)
    {
        return $this->respond($data, 204);
    }

    public function respondError($message, $statusCode)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $statusCode,
            ]
        ], $statusCode);
    }

    protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->respondError($message, 401);
    }

    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->respondError($message, 403);
    }

    protected function respondNotFound($message = 'Not Found')
    {
        return $this->respondError($message, 404);
    }

    protected function respondInternalError($message = 'Internal Error')
    {
        return $this->respondError($message, 500);
    }
}
