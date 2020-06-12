<?php

namespace App\Console\Commands;

use App\AbyssForum;
use Illuminate\Console\Command;

class WeeklyDraw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raffle:draw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Raffle draw to get the winner of a forum';

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
        // get all active forums
        $forums = (new AbyssForum)->getActiveForums();

        foreach($forums as $forum) {
            // check if a forum has users registered to it
            if ($forum->hasUsers()) {

                // get all the users for the forum that are qualified for the draw
                $users = collect($forum->users)->filter(function ($user) { return $user->isQualifiedForDraw(); });

                // get the winner for the draw
                $winner = $users->random(1)->first();

                $forum->assignWinner($winner);
            }
        }

        return true;
    }

    // get all users that have qualified for the draw
    private function getQualifiedUsers($users) : array
    {
        return array_filter($users->toArray(), function($user) {
            return (bool) $user['address'] && $user['city'] && $user['country'] && $user['state'] && $user['zip_code'];
        });
    }
}
