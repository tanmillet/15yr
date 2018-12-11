<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game;
use App\Models\Game\base\VerConfig;
use App\Server\UploadeFile;
/**
 * Description of VerConfigModel
 *
 * @author 七彩P1
 */
class VerConfigModel extends VerConfig{
    //put your code here
    
    
    
    /*
     * 移动上传文件
     */
    public function moveFile($oldFilePathFile,$game,$pfid,$usid,$mobile_type,$filename){
        $oldFilePathFileArr  = pathinfo($oldFilePathFile);
        $newFilePath = "pack/".$game."/".$mobile_type."/".$pfid."/".$usid."/";
        $uploadFile = new  UploadeFile;
        $newFile = str_replace($uploadFile->file_type["verconfig"], $newFilePath, $oldFilePathFile);

        $newFile = str_replace($oldFilePathFileArr["basename"], $filename, $newFile);
        $uploadFile->moveFile($oldFilePathFile, $newFile);
        return $newFile;
    }
}
