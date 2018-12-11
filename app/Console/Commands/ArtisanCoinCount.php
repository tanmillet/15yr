<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 11:15
 */

namespace App\Console\Commands;

use App\Models\Count\CoinCountModel;
use Illuminate\Console\Command;

class ArtisanCoinCount extends Command
{
    protected $name = 'sync:coinCount';
    public function handle()
    {
        (new CoinCountModel())->arstianCount();
    }
}