<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game;
use App\Models\Game\base\Email;
use App\Models\Game\base\ObGoods;
use App\Models\Game\BackPageModel;
//use App\Log\Facades\Logger;
/**
 * Description of EmailModel
 *
 * @author 七彩P1
 */
class EmailModel {
    /*
     * 获取用户邮件
     */
    public function getEmail($uid,$type=0){
        $where['ruid'] = $uid;
        $where['type'] = $type;
        $where['is_delete'] = 0;
        $afterTime = time()- (env("READEMAILDAY",30) *24*3600);
        $email = (new Email())->where($where)->where("created_at",">=",date("Y-m-d H:i:s",$afterTime))->orderBy("created_at","desc")->limit(env("READEMAILCOUNT",60))->get();
        $ret = FALSE;
        if($email){
            $ret =$email->toArray();
            foreach($ret as $k=>$v){
                if($v['send_type'] == 2){//如果有附件的话
                    $ret[$k]['obgoods_info'] = json_decode($v['obgoods_info'],true);
                    if(!empty($ret[$k]['obgoods_info'])){
                        foreach($ret[$k]['obgoods_info'] as $ke =>$value){
                            $ret[$k]['obgoods_info'][$ke]["goods_img"] = env("CDNURL").$value["goods_img"];
                        }
                    }
                }
            }
        }
        return $ret;
    }
    /*
     * 发送邮件
     * $goods_id_str 格式 道具表的ID:送的数量
     */
    public function sendEmail($uid,$content,$type=0,$goods_id_str="",$suid=0){
        if($goods_id_str){
            $sendType = 2;
            $obgoods_arr = (new ObGoods())->useGoodsSetObs($goods_id_str);//设置 附件内容
            $upt['obgoods_id_str'] = $obgoods_arr['obgoods_str_id'];
            $upt['obgoods_info'] = json_encode($obgoods_arr['obgoods_info']);
        }else{
            $sendType = 1;
            $upt['obgoods_id_str'] = '';
        }
        
        $upt['suid'] = $suid;
        $upt['ruid'] = $uid;
        
        $upt['send_type'] = $sendType;
        $upt['content'] = $content;
        $upt['created_at'] = date("Y-m-d H:i:s");
        $upt['status'] = 0;
        $upt['type'] =$type;
        $id =(new Email())->insertGetId($upt);
        return $id;
    }
    
    
    /*
     * 领取附件
     */
    public function receiveEmail($uid,$email_id){
        $result = ["status"=>FALSE,"error"=>"","data"=>""];
        $baseEmail = (new Email());
        $emailInfo =$baseEmail->where("ruid",$uid)->where("id",$email_id)->first();
        if(empty($emailInfo) || $emailInfo->send_type ==1 || $emailInfo->is_receive ==1 || !$emailInfo->obgoods_id_str){
            return $result;
        }
        $upt = ["is_receive"=>1,"obgoods_id_str"=>"","obgoods_info"=>"","status"=>"1"];
        $flag =$baseEmail->where("ruid",$uid)->where("id",$email_id)->where("is_receive",0)->update($upt);
        if($flag){
            $baseObGoods = new ObGoods();
            $obGoodsInfoArr = $baseObGoods->getGiveGoods($emailInfo->obgoods_id_str);
            $bpModel = new BackPageModel;
            foreach($obGoodsInfoArr as $ke=>$obGoodsInfo){
                if($obGoodsInfo){//道具存在
                    $bpModel->ObgChangeUserBackPage($uid,$obGoodsInfo,0,1,5,$obGoodsInfo['give_num'],1);//把道具放到背包里面  然后使用
                }else{
                    //Logger::info("用户{$uid}邮箱ID{$email_id}不能领取的OBgoodid".json_encode($obGoodsInfo),"receiveEmail");
                }
            }
            $result["status"] = true;
        }
        return $result;
    }
    
    
    /*
     * 全部删除
     */
    public function deleteEmail($uid,$email_id=0){
        $result = ["status"=>true,"error"=>"","data"=>""];
        $where['ruid'] = $uid;
        $email_id && $where['id'] = $email_id;
        $where['obgoods_id_str'] = "";
        $where['type'] = 0;
        (new Email)->where($where)->update(array("is_delete"=>1));
        return $result;
    }
    
    
    /*
     * 自动领取过期有附件邮件
     */
    public function autoReceiveEmail(){
        $where['is_delete'] = 0;
        $where['send_type'] = 2;
        $where['is_receive'] = 0;
        $afterTime = time()- (env("READEMAILDAY",30) *24*3600);
        $email = (new Email())->where($where)->where("created_at","<=",date("Y-m-d H:i:s",$afterTime))->select("ruid","id")->get();
        if(!$email){
            return FALSE;
        }
        $emailArray = $email->toArray();
        foreach($emailArray as $value){
            $this->receiveEmail($value['ruid'], $value['id']);
        }
        return true;
    }
 
    /*
     * 全部领取
     */
    public function receiveAllEmail($uid){
        $where['is_delete'] = 0;
        $where['send_type'] = 2;
        $where['ruid'] = $uid;
        $where['is_receive'] = 0;
        $email = (new Email())->where($where)->select("ruid","id")->get();
        if(!$email){
            return FALSE;
        }
        $emailArray = $email->toArray();
        foreach($emailArray as $value){
            $this->receiveEmail($value['ruid'], $value['id']);
        }
        return true;
    }
    
    
    //查看
    public function readEmail($uid,$email_id){
        $where['ruid'] = $uid;
        $where['id'] = $email_id;
        $where['status'] = "0";
        (new Email)->where($where)->update(array("status"=>1));
        return true;
    }
}

