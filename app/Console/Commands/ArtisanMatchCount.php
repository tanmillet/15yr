<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Count\MatchCountModel;
/**
 * Description of ArtisanMatchCount
 *
 * @author 七彩P1
 */
class ArtisanMatchCount extends Command{
    protected $name = 'sync:matchCount';
    public function handle()
    {
        (new MatchCountModel)->arstianCount();
    }
}
