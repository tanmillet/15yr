<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;
use Illuminate\Console\Command;
/**
 * Description of ArtisanMoneyCount
 *
 * @author 七彩P1
 */
use App\Models\OnlineModel;
use App\Models\Count\MoneyCountModel;
use App\Models\Count\MoneyCountInfoModel;
class ArtisanMoneyCount  extends Command{
    protected $name = 'sync:moneyCount';
    public function handle()
    {
        (new MoneyCountModel)->arstianCount();
        (new MoneyCountInfoModel)->arstianCount();
    }
}
