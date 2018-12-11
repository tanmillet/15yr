<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 11:01
 */

namespace App\Models\Count;

use App\Models\Count\base\CoinCountType;
use App\Models\Game\base\userInfo;
use App\Models\Game\base\userGame;
use App\Models\Count\base\CoinCount;
use Illuminate\Support\Facades\DB;

class CoinCountModel
{
    /**
     * 定时统计玩家金币
     * @return false|string
     */
    public function arstianCount()
    {
        
        $date = date("Y-m-d",strtotime("-1 day"));
        
        $type = (new CoinCountType())->where('active',1)->first()->toArray(); // 获取当前激活的统计类型
        $types = json_decode($type['type']);
        $games = config('game.game');
        $string = '';
        foreach ($types as $k=>$v){
            if(isset($types[$k+1])){
                $string.='sum(case when uchip>='.$v.' and uchip<'.$types[$k+1].' then 1 else 0 end) as "'.$v.'-'.$types[$k+1].'",';
            }else {
                $string.='sum(case when uchip>='.$v.' then 1 else 0 end) as "'.$v.'-∞"';
            }
        }
        $userinfo = (new userInfo())->getTable();
        $usergame = (new userGame());
        foreach ($games as $k=>$v){
            $res[$v['value']]["all"] = $usergame->rightJoin($userinfo,$userinfo.'.uid',$usergame->getTable().'.uid')->select(DB::raw($string))->where('game',$v['value'])->first()->toArray();
            $res[$v['value']]["active"] = $usergame->rightJoin($userinfo,$userinfo.'.uid',$usergame->getTable().'.uid')->select(DB::raw($string))->where('game',$v['value'])
                                        ->where('utime',">=", strtotime($date ." 00:00:00"))->where('utime',"<=", strtotime($date ." 23:59:59"))->first()->toArray();
        }
        //$date = date('Y-m-d');
        $type = $type['id'];
        $coinc = new CoinCount();
        foreach ($res as $k=>$v){
            if($coinc->where('date',$date)->where('game',$k)->first() == false) {
                $datas = json_encode(array_values($v["all"]));
                $activeDatas = json_encode(array_values($v["active"]));
                $coinc->insert(['date' => $date, 'game' => $k, 'type' => $type, 'datas' => $datas, 'active_datas' => $activeDatas]);
            }
        }
    }
}