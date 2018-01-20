<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;

use App\Core\BaseController;
use App\Contracts\Services\Logic\IUserAppService;
use Dingo\Api\Exception\StoreResourceFailedException as Exception;

class UserController extends BaseController
{
    private $userAppService;    

    public function __construct(IUserAppService $userAppService)
    {
        $this->userAppService = $userAppService;        
    }

    public function save(Request $request) {
        $data = $request->all();
        $user = $this->userAppService->save($data);

        if (! $user) {
          return $this->errorResponse('Could not create new user.', 200, $this->userAppService->getErrors());
        }

        return $this->successResponse('Successfully stored user.', $user);
    }

    public function getAll(Request $request)
    {
        $users = $this->userAppService->getAll(['profiles']);
        return $this->successResponse('', $users);
    }

    public function getById(Request $request, $id) {
        $user = $this->userAppService->getById($id, ['profiles']);

        if(! $user) {
            return $this->errorResponse('Could not found user.', 200, null);
        }

        return $this->successResponse('', $user);
    }

    public function delete(int $userId) {
        $user = $this->userAppService->delete($userId);

        if(! $user) {
            return $this->errorResponse('Could not found user.', 200, $this->userAppService->getErrors());
        }

        return $this->successResponse('Successfully deleted user.', null);
    }
}
