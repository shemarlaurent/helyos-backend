<?php

namespace App\Console\Commands;

use App\Test;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DoTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'veryfy';

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
     * @return mixed
     */
    public function handle()
    {
        Test::create([
            'log' => Str::random(10),
        ]);
        return true;
    }
}
