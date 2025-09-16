<?php

namespace app\controllers;

use app\services\MatchService;
use core\Controller;

/**
 * MatchController handles all match-related operations in the application.
 * It manages the creation, listing, updating, and deletion of matches.
 */
class MatchController extends Controller
{
    /** @var MatchService Service class that handles match-related business logic */
    private $matchService;

    /**
     * Constructor initializes the MatchController with a new MatchService instance.
     */
    public function __construct()
    {
        $this->matchService = new MatchService();
    }

    /**
     * Displays a list of all matches in the system.
     * 
     * @return void
     */
    public function index()
    {
        $matches = $this->matchService->getAllMatches();
        $this->view('match/index', ['matches' => $matches]);
    }

    /**
     * Handles the creation of a new match.
     * 
     * This method processes POST requests to create a new match with the following data:
     * - Match date and time
     * - Location
     * - Match fee and fee payer
     * - List of participating players
     * 
     * Requirements:
     * - Minimum 4 players must be selected
     * - All required fields must be provided
     * 
     * @return void
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $match_date = $_POST['match_date'];
            $match_time = $_POST['match_time'];
            $location = $_POST['location'];
            $match_fee = $_POST['match_fee'];
            $match_fee_payer = $_POST['match_payer'];
            $player_ids = $_POST['players'] ?? [];

            if (count($player_ids) < 4) {
                header("Location: /halisaha/index.php?controller=dashboard&error=En%20az%204%20oyuncu%20seçmelisiniz.");
                exit();
            }

            if ($this->matchService->addMatchWithPlayers($match_date, $match_time, $location, $match_fee, $match_fee_payer, $player_ids)) {
                header("Location: /halisaha/index.php?controller=dashboard&success=Maç%20ve%20oyuncular%20başarıyla%20eklendi.");
            } else {
                header("Location: /halisaha/index.php?controller=dashboard&error=Maç%20eklenirken%20bir%20hata%20oluştu.");
            }
            exit();
        }
    }

    /**
     * Handles the updating of an existing match.
     * 
     * This method:
     * 1. Retrieves the match ID from the GET parameters
     * 2. Processes POST requests to update match details
     * 3. Displays the edit form for the specified match
     * 
     * @return void
     */
    public function edit()
    {
        $matchId = $_GET['id'] ?? null;
        if (!$matchId) {
            header("Location: /halisaha/index.php?controller=match&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->matchService->updateMatch($matchId, $_POST);
            header("Location: /halisaha/index.php?controller=match&action=index");
            exit();
        }

        $match = $this->matchService->getMatchById($matchId);
        $this->view('match/edit', ['match' => $match]);
    }

    /**
     * Handles the deletion of a match.
     * 
     * This method:
     * 1. Retrieves the match ID from the GET parameters
     * 2. Deletes the specified match if it exists
     * 3. Redirects back to the match listing page
     * 
     * @return void
     */
    public function delete()
    {
        $matchId = $_GET['id'] ?? null;
        if ($matchId) {
            $this->matchService->deleteMatch($matchId);
        }

        header("Location: /halisaha/index.php?controller=match&action=index");
        exit();
    }
}
