<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Server\UploadeFile;
/**
 * Description of CommonController
 *
 * @author 七彩P1
 */
class CommonController extends BaseController{
    public function UploadFile($uploadType,Request $request){
        $input_name = $request->has("input_name")?$request->get("input_name"):"";
        $img_path = $request->has("img_path")?$request->get("img_path"):"";
        $file_name = $request->has("file_name")?$request->get("file_name"):"";
        $file=(new UploadeFile)->file($input_name, $uploadType, $img_path, $file_name);
        if($file){
            return $this->retJson(200, '上传成功!',array("file_name"=>$file));
        } else {
            return $this->retError(403, '修改失败');
        }
    }
}
