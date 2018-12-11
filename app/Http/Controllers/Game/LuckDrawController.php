<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/9/29
 * Time: 11:51
 */

namespace App\Http\Controllers\Game;

use App\Http\Controllers\BaseController;
use App\Models\Count\base\LuckDraw;
use App\Models\Game\base\ScareBuyLog;
use App\Models\Game\base\userInfo;
use App\Models\Game\base\userScareBuyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LuckDrawController extends BaseController
{
    public function index(Request $request)
    {
        // 获取当日开奖次数
        $date_type = "yyyy-mm-dd"; // 设置日期格式
        $where[0] = ['game',2];
        $where[1] = ['date',date('Y-m-d',strtotime("-1 day"))]; //默认查询前一天的数据
        if($request->get('game')){
            $where[0] = ['game',$request->get('game')];
        }
        if($request->get('date')){
            $where[1] = ['date',$request->get('date')];
        }
        $datas = (new LuckDraw())->where($where)->get();
        return view('admin.game.luckdraw.index',['datas'=>$datas,'date_type'=>$date_type]);
    }

    public function explord(Request $request) // 传入游戏，抽奖物品，日期，获取获奖用户的ID
    {
        $game = $request->get('ga');
        $goodid = $request->get('go');
        $date = $request->get('da');
        $gname = $request->get('na');
        $scarebuy = new ScareBuyLog();
        $userInfo = new userInfo();
        $res = $scarebuy->select('prize_uid')->where('game',$game)->where('scare_buy_id',$goodid)->where('order_ftime','>',strtotime($date))
            ->where('order_ftime','<=',strtotime($date)+86400)->where("prize_uid",">",$userInfo->aiUserId)->get()->toArray();
        $title = array('id');
        $this->exported('夺宝('.$gname.')'.date('YmdHis').'.xls',$title,$res);
    }

    public function exported($filename, $titlearr, $dataarr)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=" . $filename);
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp,$titlearr);
        $datas = $dataarr;
        foreach ($datas as $val)
        {
            fputcsv($fp,$val);
        }
        ob_flush();
        flush();
        ob_end_clean();
    }
}