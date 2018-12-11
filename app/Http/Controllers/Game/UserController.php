<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author 七彩P1
 */

namespace App\Http\Controllers\Game;

use App\Models\Game\base\LoginLog;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Game\base\userGame;
use App\Models\Game\base\userInfo;
use App\Models\Game\GameUserModel;
use App\Models\Game\MoneyModel;
use App\Models\Game\base\CoinLog;
use Illuminate\Support\Facades\DB;
use App\Models\Game\CommonModel;
use App\Models\Game\base\UserDayLog;

class UserController extends BaseController
{

    public function index(Request $request)
    {
        $uTable = (new userInfo)->getTable();
        $users = userInfo::query();
        $ugTable = (new userGame)->getTable();
        $users->leftjoin($ugTable, $uTable . ".uid", "=", $ugTable . ".uid");
        if ($request->has("uid") == TRUE) {
            $users->where($ugTable . ".uid", $request->get("uid"));
        }
        if ($request->has("uname") == TRUE) {
            $users->where($uTable . ".uname", 'LIKE', '%' . $request->get("uname") . '%');
        }
        if ($request->has("sdate") == TRUE) {
            $users->where($uTable . ".urtime", ">=", strtotime($request->get("sdate")));
        }
        if ($request->has("fdate") == TRUE) {
            $users->where($uTable . ".urtime", "<=", strtotime($request->get("fdate")));
        }

        $pager = $users->orderBy($ugTable . '.uid', 'DESC')->paginate();

        return view('admin.gameUser.userList', compact('pager'));
    }


    /*
     * 操作用户身上金币
     */
    public function showMoney($uid)
    {
        $gameUser = new GameUserModel();
        $data['user'] = $gameUser->getUserInfo($uid);
        if (!$data['user']) {
            return FALSE;
        }
        $ip = "";
        $user_day_log = (new UserDayLog)->where('uid', $uid)->where("is_new_user", 1)->select("login_ip")->first();
        $user_day_log && $ip = long2ip($user_day_log->login_ip);
        $data['moneyType'] = (new MoneyModel())->typeName;
        $data['moneyFlagType'] = (new MoneyModel())->moneyFlagType;
        return view('admin.gameUser.showMoney', $data)->with("ip", $ip);
    }


    public function operateMoney(Request $request)
    {

        $all = $request->all();
        $validateRule = [
            'moneyType' => 'required',
            'moneyFlagType' => 'required',
            'money' => 'required|integer',
        ];
        $errorMsg = [
            'moneyType.required' => '修改货币类型必填',
            'moneyFlagType.required' => '修改类型必填',
            'money.required' => '数量不能为空',
            'money.integer' => '数量必须是整数',
        ];
        $this->validate($request, $validateRule, $errorMsg);
        extract($all);
        $flag = (new MoneyModel())->operateMoney($uid, $money, "admin", $moneyType, $moneyFlagType);
        $this->Log("修改用户" . $uid, $all);
        if ($flag) {
            return $this->success();
        } else {
            return $this->retError(403, '修改失败');
        }
    }


    //用户金币变动表
    public function showMoneyLog($uid, Request $request)
    {
        $coinLog = new CoinLog;
        $where = array();
        $where["uid"] = $uid;

        if ($request->has("sdate") != TRUE) {
            $request->offsetSet("sdate", date("Y-m-d 00:00:00", strtotime("-7 day")));
        }

        if ($request->has("fdate") != TRUE) {
            $request->offsetSet("fdate", date("Y-m-d H:i:s"));
        }

        if ($request->has("moneytype") != TRUE) {
            $request->offsetSet("moneytype", "uchip");
        }
        if ($request->get("code")) {
            $code = $request->get("code");
        } else {
            $code = 0;
        }

        $stime = strtotime($request->get("sdate"));
        $ftime = strtotime($request->get("fdate"));
        $date_arr = (new CommonModel)->getDate($stime, $ftime);
        $cTable = $coinLog->getTable();
        $sql = "";
        $dbConfig = config("database.connections." . $coinLog->getConnectionName());
        $db = $dbConfig['database'];
        $m_type = (new MoneyModel())->type;
        $moneytype = $m_type[$request->get("moneytype")];
        $limit_time = strtotime("2018-09-07");
        foreach ($date_arr as $date) {
            //$coinLogCountObj = $connection->table($cTable.$date) ;

            $tablename = $cTable . $date;
            if (strtotime($date) < $limit_time) {
                $sql .= " select changecoin,time,gameid,code,uid,'' as game,'' as gametype from {$db}." . $tablename . " where uid=" . $uid . " and moneytype=" . $moneytype . " and time >=" . strtotime($request->get("sdate")) . " and  time <=" . strtotime($request->get("fdate"));
            } else {
                $sql .= " select changecoin,time,gameid,code,uid,game,gametype from {$db}." . $tablename . " where uid=" . $uid . " and moneytype=" . $moneytype . " and time >=" . strtotime($request->get("sdate")) . " and  time <=" . strtotime($request->get("fdate"));
            }
            $code && $sql .= " and  code={$code}";
            $sql .= " union all";

        }
        $sql = rtrim($sql, 'union all');
        $pager = DB::table(DB::raw("($sql) as a"))->mergeBindings($coinLog->getQuery($sql))
            ->orderBy('time', 'desc')->paginate(60);

        $moneyType = (new MoneyModel())->typeName;
        return view('admin.gameUser.showMoneyLog', compact('pager', "uid", 'm_type', 'moneyType', 'pager'));
    }

    public function userlog($uid, Request $request)
    {
        if ($request->has("sdate") != TRUE) {
            $request->offsetSet("sdate", date("Y-m-d 00:00:00", strtotime("-7 day")));
        }
        if ($request->has("fdate") != TRUE) {
            $request->offsetSet("fdate", date("Y-m-d H:i:s"));
        }
        $stime = $request->get("sdate");
        $ftime = $request->get("fdate");
        $where[0] = ['uid',$uid];
        $where[1] = ['login_time','>=',$stime];
        $where[2] = ['login_time','<=',$ftime];
        $log = new LoginLog();
        $pager = $log->where($where)->paginate(30);
        $game = config('game.gameid');
        $pfid = config('game.pfid');
        $login_type = config('game.login_type');
        return view('admin.gameUser.showloginlog', compact('pager','uid','game','pfid','login_type'));
    }
    
    
    public function loginLog(Request $request){
         $loginLog = (new LoginLog);
        if ($request->has("uid") == TRUE) {
            $loginLog =$loginLog->where("uid", $request->get("uid"));
        }
        if ($request->has("game") != TRUE) {
            $request->offsetSet("game",2);
        }
        $loginLog =$loginLog->where( "game",$request->get("game") );
        
        if ($request->has("sdate") == TRUE) {
            $loginLog =$loginLog->where( "login_time", ">=", $request->get("sdate"));
        }
        if ($request->has("fdate") == TRUE) {
            $loginLog =$loginLog->where("login_time", "<=", strtotime($request->get("fdate")));
        }
        if ($request->has("login_ip") == TRUE) {
            $loginLog =$loginLog->where("login_ip", "=", $request->get("login_ip"));
        }
        if ($request->has("login_type") == TRUE) {
            $loginLog =$loginLog->where("login_type", "=", $request->get("login_type"));
        }
         $pager = $loginLog->orderBy( 'login_time', 'DESC')->paginate();
         
        $login_type = config('game.login_type');
        $game = config('game.gameid');
        $pfid = config('game.pfid');
        
        return view('admin.gameUser.loginLogList',  compact('pager','uid','game','pfid','login_type'));
    }
}
