<?php

namespace app\services;

use app\models\DashboardModel;

class DashboardService
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
    }

    /**
     * Kasa bakiyesini hesaplar.
     * @return float
     */
    public function getCashBalance(): float
    {
        $totalPayments = $this->dashboardModel->getTotalPayments();
        $totalMatchFees = $this->dashboardModel->getTotalMatchFees();
        return $totalPayments - $totalMatchFees;
    }

    /**
     * Oyuncuların bakiyeleri ile birlikte listesini döndürür.
     * @return array
     */
    public function getPlayersWithBalance(): array
    {
        return $this->dashboardModel->getAllPlayersWithBalance();
    }

    /**
     * Oyuncuları sık ödeme yapanlara göre önceliklendirir ve iki gruba böler.
     * @return array
     */
    public function getSortedPlayerChunks(): array
    {
        $players = $this->getPlayersWithBalance();

        // Öncelikli oyuncular
        $priorityPlayers = [33, 19, 1]; // Erkunt: 33, Mahmut: 19, Alpay: 1

        usort($players, function($a, $b) use ($priorityPlayers) {
            $aPriority = array_search($a["player_id"], $priorityPlayers);
            $bPriority = array_search($b["player_id"], $priorityPlayers);

            if ($aPriority === false || $bPriority === false) {
                // If either player is not found, prioritize the first available one
                return 0;
            }
            return $aPriority - $bPriority;
        });

        return array_chunk($players, ceil(count($players) / 2));
    }

    /**
     * Grafik için gerekli veriyi hazırlar.
     * @return array
     */
    public function getChartData(): array
    {
        return [
            'total_payments' => $this->dashboardModel->getTotalPayments(),
            'total_match_fees' => $this->dashboardModel->getTotalMatchFees()
        ];
    }
    /**
     * Negatif bakiyeye sahip oyuncuların listesini getirir.
     * @return array
     */
    public function getNegativeBalancePlayers(): array
    {
        return $this->dashboardModel->getNegativeBalancePlayers();
    }
}

