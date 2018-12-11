<?php
namespace App\Http\Controllers;

use Response;
use Illuminate\Http\JsonResponse;
use App\Models\OpLog;
use App\Utils\RequestUtils;

class BaseController extends Controller{

    public function __construct()
    {
        view()->share('base_url', config("app.url"));
        view()->share('assets_url', config("app.url"));
    }

    protected function retJson($status=1, $msg = '', $data = [])
    {
        return Response::json(['status'=>$status, 'msg'=> $msg, 'data' => $data]);
    }

    protected function retError($status=403, $msg = '')
    {
        return new JsonResponse($msg, $status);
    }

    protected function success()
    {
        return $this->retJson(200, '操作成功!');
    }

    protected function validateError(array $error)
    {
        return new JsonResponse($error, 422);
    }
    
    protected function Log($description, $params)
    {
        $restfulParams = RequestUtils::toRestfulParams();
        $url = $restfulParams[RequestUtils::URL];
        $controller = $restfulParams[RequestUtils::CONTROLLER];
        $className = $restfulParams[RequestUtils::CLASS_NAME];
        $classMethod = $restfulParams[RequestUtils::CLASS_METHOD];
        $method = $restfulParams[RequestUtils::METHOD];
        $realMethod = $restfulParams[RequestUtils::REAL_METHOD];
        OpLog::addOpLog($url, $controller, $className, $classMethod, $method, $realMethod, $description, $params);
    }
}