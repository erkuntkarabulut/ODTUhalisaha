<?php

namespace app\services;

use app\models\PlayerModel;

class PlayerService
{
    private $playerModel;

    public function __construct()
    {
        $this->playerModel = new PlayerModel();
    }

    public function getAllPlayers()
    {
        return $this->playerModel->getAll();
    }
    public function getSortedPlayerChunks(): array
    {
        $players = $this->getAllPlayers();
    
        // Öncelikli oyuncular
        $priorityPlayers = [33, 19, 1];
    
        // Öncelikli oyuncuları ve diğer oyuncuları ayrı gruplara ayır
        $priorityList = [];
        $normalList = [];
    
        foreach ($players as $player) {
            if (in_array($player["player_id"], $priorityPlayers)) {
                $priorityList[] = $player;
            } else {
                $normalList[] = $player;
            }
        }
    
        // Öncelikli oyuncular kesinlikle en başta olacak şekilde birleştir
        $sortedPlayers = array_merge($priorityList, $normalList);
    
        // Eğer iki gruba bölmek gereksizse, doğrudan sıralı listeyi döndür
        return $sortedPlayers;
    }

    public function addPlayer($name, $email, $phone_number)
    {
        return $this->playerModel->addPlayer($name, $email, $phone_number);
    }

    public function getPlayerById($id)
    {
        return $this->playerModel->getPlayerById($id);
    }

    public function updatePlayer($id, $name, $email, $phone_number)
    {
        return $this->playerModel->updatePlayer($id, $name, $email, $phone_number);
    }

    public function deletePlayer($id)
    {
        return $this->playerModel->deletePlayer($id);
    }
}
