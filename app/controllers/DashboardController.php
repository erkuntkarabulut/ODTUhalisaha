<?php

namespace app\controllers;

use app\services\DashboardService;
use core\Controller;

/**
 * DashboardController handles the main dashboard functionality of the application.
 * It manages the display of financial data, player information, and chart statistics.
 */
class DashboardController extends Controller
{
    /** @var DashboardService Service class that handles dashboard-related business logic */
    private $dashboardService;

    /**
     * Constructor initializes the DashboardController with a new DashboardService instance.
     */
    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    /**
     * Displays the main dashboard view with various financial and player statistics.
     * 
     * This method gathers the following data:
     * - Current cash balance
     * - Sorted player chunks (grouped player data)
     * - List of players with their balances
     * - Chart data for visualization
     * - List of players with negative balances
     * 
     * @return void
     */
    public function index()
    {
        $cashBalance = $this->dashboardService->getCashBalance();
        $playerChunks = $this->dashboardService->getSortedPlayerChunks();
        $players = $this->dashboardService->getPlayersWithBalance();
        $chartData = $this->dashboardService->getChartData();
        $negativePlayers = $this->dashboardService->getNegativeBalancePlayers();

        $this->view('dashboard/index', [
            'cashBalance' => $cashBalance,
            'chartData' => $chartData,
            'playerChunks' => $playerChunks,
            'players' => $players,
            'negativePlayers' => $negativePlayers
        ]);
    }
}
