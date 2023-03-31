<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use DateTimeZone;
use DateTime;

class Currentime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'current:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Storing current time in firebase';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $timezone_name = timezone_name_from_abbr("", 330*60, false);
        $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
        $date->setTimeZone(new DateTimeZone('UTC')); 
        $date = $date->format('Y-m-d H:i:s.u');
        $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
        $factory = (new Factory())->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        $database = $factory->createDatabase(); 
        $newPost =  $database
                    ->getReference('currenttime')
                    ->set([
                    'time' => $date,
                    ]);
         $this->info("time update");
         Log::info("time update");
    }
}
