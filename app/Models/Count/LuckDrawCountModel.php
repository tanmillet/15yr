<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 10:58
 */

namespace App\Models\Count;

use App\Models\Count\base\LuckDraw;
use App\Models\Game\base\ScareBuyLog;
use App\Models\Game\base\userInfo;
use Illuminate\Support\Facades\DB;
use App\Models\Game\base\userScareBuyLog;

class LuckDrawCountModel
{
    /**
     * 定时统计幸运夺宝
     * @return string
     */
    public function arstianCount()
    {
        $luckdraw = new LuckDraw();
        $res = $luckdraw->where('date',date('Y-m-d'))->first();  //查询今日是否已经统计数据
        if($res)
            return;
        $datas = $this->getData();
        $data = array();    
        $beforedate = date('Y-m-d 00:00:00',strtotime('-1 day'));
        foreach ($datas as $k=>$v){
            $data[$k]['game'] = $v['game'];
            $data[$k]['goodid'] = $v['goodid'];
            $data[$k]['date'] = $beforedate;
            $data[$k]['goodname'] = $v['scare_buy_name'];
            $data[$k]['dayopens'] = $v['dayopens'];
            $data[$k]['playnums'] = $v['playnums'];
            $data[$k]['robotnums'] = $v['robotnums'];
            $data[$k]['dayshuts'] = $v['dayshuts'];
            $data[$k]['uids'] = 0;
        }
        $luckdraw->insert($data);
    }

    public function getData()
    {
        $scarebuy = new ScareBuyLog();
        $beforedate = date('Y-m-d',strtotime('-1 day')); // 昨日00:00
        $afterdate = date('Y-m-d'); //当日00:00`
        $beforetime = strtotime($beforedate);
        $aftertime = strtotime($afterdate);
        $userrobot = (new userInfo())->aiUserId;
        $datas = $scarebuy
            ->select('game','scare_buy_name','scare_buy_id as goodid',
                DB::raw('curdate() AS date,count(*) as dayopens,
                sum( case when prize_uid > '.$userrobot.' then 1 else 0 end ) AS dayshuts '))
            ->where('order_ftime','>',$beforetime)
            ->where('order_ftime','<=',$aftertime)
            ->groupBy('scare_buy_id')->groupBy('game')
            ->get()->toArray();
        // 获取玩家夺宝次数
        $userscarelog = new userScareBuyLog();
        $ltable = $userscarelog->getTable();
        $rtable = $scarebuy->getTable();
        $data = $userscarelog
            ->leftJoin($rtable,$ltable.'.scare_buy_log_id',$rtable.'.id')
            ->select(DB::raw('sum(case when uid > '.$userrobot.' then 1 else 0 end) as playnums,
            sum(case when uid <= '.$userrobot.' then 1 else 0 end) as robotnums,'.$ltable.'.game,scare_buy_id as goodid,curdate() as date,scare_buy_name'))
            ->groupBy('scare_buy_id')->groupBy($ltable.'.game')
            ->where($ltable.'.created_at','>',$beforedate)
            ->where($ltable.'.created_at','<=',$afterdate)
            ->get()->toArray();
        $datas = array_combine(array_column($datas,"goodid"),$datas);
        foreach ($data as $ke=>$vl){
			if(!isset($datas[$vl['goodid']])){
				$datas[$vl['goodid']]['game'] = $vl['game'];
				$datas[$vl['goodid']]['goodid'] = $vl['goodid'];
				$datas[$vl['goodid']]['scare_buy_name'] = $vl['scare_buy_name'];
				$datas[$vl['goodid']]['date'] = $vl['date'];
				$datas[$vl['goodid']]['dayopens'] = 0;
				$datas[$vl['goodid']]['dayshuts'] = 0;
			}
            $datas[$vl['goodid']]['playnums'] = $vl['playnums'];
            $datas[$vl['goodid']]['robotnums'] = $vl['robotnums'];
        }
        return $datas;
    }
}