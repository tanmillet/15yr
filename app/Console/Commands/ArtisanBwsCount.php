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
use App\Models\Count\BwsCountModel;
class ArtisanBwsCount extends Command{
    protected $name = 'sync:bwsCount';
    public function handle()
    {
        (new BwsCountModel)->arstianCount();
    }
}
