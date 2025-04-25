<?php

namespace App\Traits;

use App\Models\AmateurGame;
use App\Models\AmateurGamePlayer;
use App\Models\AmateurJoinLinks;
use App\Models\AmateurPlayerGroup;
use Illuminate\Support\Facades\Auth;

trait JoinLinkTrait
{
    private function generateRandomCode($length = 10) {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
    }

    public function join_cancel($type, $game_id)
    {
        if($type == "manual") {

            $game = AmateurGame::with(['homeTeam', 'awayTeam'])->where('join_link_id',$game_id)->first();

            AmateurGamePlayer::where('game_id', $game->id)->where('user_id', Auth::id())->delete();
        } else {
            $game = AmateurJoinLinks::findOrFail($game_id);
            AmateurPlayerGroup::where('game_id', $game_id)->where('user_id', Auth::id())->delete();
            AmateurGamePlayer::where('link_id', $game_id)->delete();
        }
    }

    public function getGameWithJoinedStatus($game, $modelMain, $modelPlayer, $relation, )
    {


        $joined = $modelPlayer::where('game_id', $game->id)->where('user_id', Auth::id())->exists();

        return compact('game', 'joined');
    }

    private function redirectToGamePage($game_id, $code = null, $message = null, $type)
    {
        if($type=="manual"){
            $game = AmateurGame::where('join_link_id', $game_id)->first();
            $gameData = $this->getGameWithJoinedStatus($game, new AmateurGame(), new AmateurGamePlayer(), ['homeTeam', 'awayTeam']);
        } else {
            $game = AmateurJoinLinks::where('id', $game_id)->first();
            $gameData = $this->getGameWithJoinedStatus($game, new AmateurJoinLinks(), new AmateurPlayerGroup(), []);
        }
        $game = $gameData['game'];

        return redirect()->back()
            ->with(['success' => $message])
            ->with(['joined' => $gameData['joined']]);

    }

    private function handleJoinProcess($game, $user_id, $code = null, $playerModel, $create, $textIf,$textElse, $type)
    {

        $joined = $playerModel::where('game_id', $game->id)->where('user_id', $user_id)->exists();


        if (!$joined) {
            $playerModel::create($create);

        }


    }
}
