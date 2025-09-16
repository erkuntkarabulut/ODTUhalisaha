<?php

namespace app\controllers;

use app\services\PlayerService;
use core\Controller;

/**
 * PlayerController handles all player-related operations in the application.
 * It manages the creation, listing, updating, and deletion of players.
 */
class PlayerController extends Controller
{
    /** @var PlayerService Service class that handles player-related business logic */
    private $playerService;

    /**
     * Constructor initializes the PlayerController with a new PlayerService instance.
     */
    public function __construct()
    {
        $this->playerService = new PlayerService();
    }

    /**
     * Displays a list of all players in the system.
     * 
     * @return void
     */
    public function index()
    {
        $players = $this->playerService->getAllPlayers();
        $this->view('player/index', ['players' => $players]);
    }

    /**
     * Handles the creation of a new player.
     * 
     * This method processes POST requests to create a new player with:
     * - Name
     * - Email
     * - Phone number
     * 
     * @return void
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->playerService->addPlayer($_POST['name'], $_POST['email'], $_POST['phone_number']);
            header("Location: /halisaha/index.php?controller=player&action=index&success=Oyuncu%20eklendi.");
            exit();
        }
    }

    /**
     * Displays the edit form for an existing player.
     * 
     * This method:
     * 1. Retrieves the player ID from the GET parameters
     * 2. Validates that the player exists
     * 3. Displays the edit form with the player's current information
     * 
     * @return void
     */
    public function edit()
    {
        if (!isset($_GET['id'])) {
            header("Location: /halisaha/index.php?controller=player&action=index&error=Oyuncu%20bulunamadı.");
            exit();
        }

        $player = $this->playerService->getPlayerById($_GET['id']);
        if (!$player) {
            header("Location: /halisaha/index.php?controller=player&action=index&error=Oyuncu%20bulunamadı.");
            exit();
        }

        $this->view('player/edit', ['player' => $player]);
    }

    /**
     * Handles the updating of an existing player.
     * 
     * This method processes POST requests to update player details including:
     * - Player ID
     * - Name
     * - Email
     * - Phone number
     * 
     * @return void
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_id'])) {
            $result = $this->playerService->updatePlayer($_POST['player_id'], $_POST['name'], $_POST['email'], $_POST['phone_number']);

            if ($result) {
                header("Location: /halisaha/index.php?controller=player&action=index&success=Oyuncu%20bilgileri%20güncellendi.");
            } else {
                header("Location: /halisaha/index.php?controller=player&action=index&error=Güncelleme%20başarısız.");
            }
            exit();
        }
    }

    /**
     * Handles the deletion of a player.
     * 
     * This method:
     * 1. Retrieves the player ID from the GET parameters
     * 2. Deletes the specified player if they exist
     * 3. Redirects back to the player listing page with appropriate success/error message
     * 
     * @return void
     */
    public function delete()
    {
        if (isset($_GET['id'])) {
            $result = $this->playerService->deletePlayer($_GET['id']);

            if ($result === true) {
                header("Location: /halisaha/index.php?controller=player&action=index&success=Oyuncu%20silindi.");
            } else {
                header("Location: /halisaha/index.php?controller=player&action=index&error=" . urlencode($result));
            }
            exit();
        }
    }
}
