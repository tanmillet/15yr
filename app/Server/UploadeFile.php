<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Server;
use Illuminate\Support\Facades\Input;
/**
 * Description of UploadeFile
 *
 * @author 七彩P1
 */
class UploadeFile {
    public $file_type=[
        "goods"=>"image/goods/",
        "active"=>"image/active/",
        "verconfig"=>"pack/common/",
    ];
    public function file($input_name,$file_type,$file_name="",$root_file_path=""){
        $file = Input::file($input_name);
        if ($file && $file->isValid()) {
            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            //$type = $file->getClientMimeType();     // image/jpeg
            // 上传文件
            
            $one_img_path = isset($this->file_type[$file_type])?$this->file_type[$file_type]:"";
            $filename = $file_name?$file_name:date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
            if(!$root_file_path){
                $root_file_path = env("UPLOADIMGPATH");
            }
            $file = $root_file_path.$one_img_path.$filename;
            
            Directory($root_file_path.$one_img_path);
            file_put_contents($file, file_get_contents($realPath));
            $fileUrl = $one_img_path.$filename;
        } else {
            $fileUrl ="";
        }
        
        return $fileUrl;
    }
    /*
     * 移动文件
     */
    public function moveFile($oldFilePath,$newFilePath,$rootFilePath=""){
        if(!$rootFilePath){
            $rootFilePath = env("UPLOADIMGPATH");
        }
        $pathArr   = pathinfo($newFilePath);
        Directory($rootFilePath.$pathArr["dirname"]);
        rename($rootFilePath .$oldFilePath,$rootFilePath. $newFilePath );
        return   $newFilePath;
    }
    
    
    public function showImage(){
        return view("admin.common.uploadeImg");
    }
    
    public function showFile($inputName,$action="",$uploadNum=1,$dataType="*",$uploadSuccessBack=""){
        $action = $action."?input_name=".$inputName;
        return view("admin.common.uploadeFile")->with("uploadNum",$uploadNum)->with("dataType",$dataType)->with("inputName",$inputName)->with("action",$action)->with("uploadSuccessBack",$uploadSuccessBack);
    }
}
