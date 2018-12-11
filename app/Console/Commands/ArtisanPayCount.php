<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Count\PayCountModel;
/**
 * Description of ArtisanPayCount
 *
 * @author 七彩P1
 */
class ArtisanPayCount extends Command{
    protected $name = '';
    protected $signature = 'sync:paycount {--count_type=}';
    public function handle()
    {
        $count_type = $this->option('count_type');
        (new PayCountModel)->arstianCount($count_type);
    }
}

