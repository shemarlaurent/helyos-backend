<?php


namespace App\Commands;


use App\AbyssForum;

class WeeklyRaffle
{
    public function __invoke()
    {
        // get all active forums
        $forums = (new AbyssForum)->getActiveForums();

        foreach($forums as $forum) {
            // check if a forum has users registered to it
            if ($forum->hasUsers()) {

                // get all the users for the forum that are qualified for the draw
//                $users = $this->getQualifiedUsers($forum->users);

                // get the winner for the draw
                $winner = collect($forum->users)->random(1)->first();

                $forum->assignWinner($winner);
            }
        }
    }

    // get all users that have qualified for the draw
    private function getQualifiedUsers(array $users) : array
    {
        return array_filter($users, function($user) {
            return (bool) $user->address && $user->city && $user->country && $user->state && $user->zip_code;
        });
    }
}
