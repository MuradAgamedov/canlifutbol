<?php

namespace App\Helpers;

class LeagueHelper
{
    public static function getLeagueImage($leagueId)
    {
        $imagePath = public_path().'/images/leagues_new/'.$leagueId.'.jpg';

        if (file_exists($imagePath)) {
            return asset('images/leagues_new/'.$leagueId.'.jpg');
        } else {
            return asset('images/default_league.png');
        }
    }
}
