<?php

namespace App\Core;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use League\Fractal\Serializer\ArraySerializer;

use App\Contracts\Services\Logic\IUserAppService;

class BaseController extends Controller
{
    use Helpers;

    private $userAppService;
    protected $errors = false;

    public function __construct(IUserAppService $userAppService) {
        $this->userAppService = $userAppService;
    }


    protected function serializeArray()
    {
        return new ArraySerializer();
    }

    /**
     * Return a json Response
     *
     * @param mixed $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($data, $status = 200)
    {
        return response()->json($data, $status);
    }

    /**
     * Returns a Successful response for a request
     *
     * @param string $message
     * @param mixed|null $resource
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($message, $resource = null)
    {
        $response = [
            'message' => $message,
            'errors' => $this->errors,
            'data' => ''
        ];

        if(is_array($resource) && array_key_exists('data', $resource)) {
            $response['data'] = $resource['data'];
        } else {
            $response['data'] = $resource;
        }

        return $this->jsonResponse($response);
    }

    /**
     * Returns an Error response for a request
     *
     * @param string $message
     * @param int|null $code
     * @param mixed|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $code = null, $data = null)
    {
        $response = [
            'message' => $message,
            'errors' => true,
            'data' => $data,
        ];

        if ($code) {
            $response['code'] = $code;
            return $this->jsonResponse($response, $code);
        }

        return $this->jsonResponse($response);
    }

    /**
     * Authentication response
     *
     * @param int $status
     * @param string|bool $error
     * @param mixed|null $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authResponse($status = 200, $error = false, $token = null, $extraData = null)
    {
        switch ($status) {
            case 200:
                $user = currentUser();
                return $this->jsonResponse(compact('token','user'));
                break;
            case 400:
                return $this->jsonResponse(compact('error'), $status);
                break;
            case 401:
                return $this->jsonResponse(compact('token'));
                break;
            case 403:
                return $this->jsonResponse(compact('error'), $status);
                break;
            case 404:
                return $this->jsonResponse(compact('error'), $status);
                break;
            case 500:
                return $this->jsonResponse(compact('error'), $status);
                break;
        }

        return $this->jsonResponse(['error' => 'Internal Server Error'], 500);
    }

    /**
     * Returns a bool if the versions are the same
     *
     * @param $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function version($value) {
        $version = config('api.internal_version');

        $new_version = $version > $value;

        return $this->jsonResponse(compact('new_version'));
    }
}
