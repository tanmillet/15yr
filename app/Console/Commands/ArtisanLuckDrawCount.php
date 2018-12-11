<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 11:16
 */

namespace App\Console\Commands;

use App\Models\Count\LuckDrawCountModel;
use Illuminate\Console\Command;

class ArtisanLuckDrawCount extends Command
{
    protected $name = 'sync:luckDrawCount';
    public function handle()
    {
        (new LuckDrawCountModel())->arstianCount();
    }
}