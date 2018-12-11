<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of MatchTicketLog
 *
 * @author 七彩P1
 */
class MatchTicketLog extends Model{
    //put your code here
    public $table ="match_ticket_log";
    public  $connection="mysql_three";
    public $ticket_type_arr = [
                        "1"=>"金币","2"=>"钻石","3"=>"宾王卷","4"=>"免费","7"=>"宾王赛门票"
        ];
}
