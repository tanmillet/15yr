<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Count\DayCountModel;
/**
 * Description of ArtisanDayCount
 *
 * @author 七彩P1
 */
class ArtisanDayCount extends Command{
    //put your code here
        protected $name = 'sync:daycount';
    public function handle()
    {
        (new DayCountModel)->arstianCount();
    }
}
