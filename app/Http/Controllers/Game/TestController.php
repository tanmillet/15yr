<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

use App\Models\Count\base\PayCount;
use Illuminate\Support\Facades\DB;
use App\Models\Count\PayGoodsCountModel;
/**
 * Description of TestController
 *
 * @author 七彩P1
 */
class TestController extends BaseController{
    //put your code here
    public function test(){
        (new PayGoodsCountModel)->arstianCount();
    }
}
