<?php

namespace app\controllers;

use app\services\ParticipationService;
use core\Controller;

/**
 * ParticipationController handles the management of player participation in matches.
 * It manages the addition of players to matches and validates participation requirements.
 */
class ParticipationController extends Controller
{
    /** @var ParticipationService Service class that handles participation-related business logic */
    private $participationService;

    /**
     * Constructor initializes the ParticipationController with a new ParticipationService instance.
     */
    public function __construct()
    {
        $this->participationService = new ParticipationService();
    }

    /**
     * Handles the addition of players to a match.
     * 
     * This method processes POST requests to add players to a match with validation:
     * - Validates that match ID and players are provided
     * - Ensures minimum 4 players are selected
     * - Adds the players to the specified match
     * 
     * Requirements:
     * - Match ID must be provided
     * - At least one player must be selected
     * - Minimum 4 players required for a match
     * 
     * @return void
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matchId = $_POST['match_id'];
            $players = $_POST['players'] ?? [];

            if (empty($matchId) || empty($players)) {
                header("Location: /halisaha/index.php?controller=dashboard&error=Eksik%20bilgi%20girdiniz.");
                exit();
            }

            if (count($players) < 4) {
                header("Location: /halisaha/index.php?controller=dashboard&error=En%20az%204%20oyuncu%20seçmelisiniz.");
                exit();
            }

            if ($this->participationService->addPlayersToMatch($matchId, $players)) {
                header("Location: /halisaha/index.php?controller=dashboard&success=Oyuncular%20maça%20eklendi.");
                exit();
            } else {
                header("Location: /halisaha/index.php?controller=dashboard&error=Oyuncular%20eklenirken%20hata%20oldu.");
                exit();
            }
        }
    }
}
