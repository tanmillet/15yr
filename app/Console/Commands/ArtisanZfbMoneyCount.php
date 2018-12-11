<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;
use App\Models\Count\ZfbMoneyCountModel;
use Illuminate\Console\Command;
/**
 * Description of ArtisanZfbMoneyCount
 *
 * @author 七彩P1
 */
class ArtisanZfbMoneyCount extends Command{
    //put your code here
        protected $name = 'sync:zfbmoneycount';
    public function handle()
    {
        (new ZfbMoneyCountModel())->arstianCount();
    }
}
