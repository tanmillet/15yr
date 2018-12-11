<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 11:16
 */

namespace App\Console\Commands;

use App\Models\Count\SeasonCountModel;
use Illuminate\Console\Command;

class ArtisanSeasonCount extends Command
{
    protected $name = 'sync:seasonCount';
    public function handle()
    {
        (new SeasonCountModel())->arstianCount();
    }
}