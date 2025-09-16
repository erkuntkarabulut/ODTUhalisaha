<?php

namespace app\services;

use app\models\ParticipationModel;
use app\models\MatchModel;
use core\Utils;

class ParticipationService
{
    private $participationModel;
    private $matchModel;

    public function __construct()
    {
        $this->participationModel = new ParticipationModel();
        $this->matchModel = new MatchModel();
    }

    /**
     * Oyuncuları bir maça ekler, kişi başı ödeme hesaplar ve log kaydı ekler.
     *
     * @param int $matchId Maç ID'si
     * @param array $playerIds Oyuncu ID listesi
     * @return bool
     */
    public function addPlayersToMatch(int $matchId, array $playerIds): bool
    {
        if (empty($playerIds)) {
            return false;
        }

        // Maç ücretini al
        $matchFee = $this->matchModel->getMatchFee($matchId);
        if (!$matchFee) {
            return false;
        }

        // Maça önceden gelen oyuncuları öğren
        $existingPlayerCount = $this->participationModel->getMatchPlayerCount($matchId);

        // Toplam oyuncu sayısını hesapla
        $totalPlayerCount = $existingPlayerCount + count($playerIds);

        // Kişi başına düşen ücreti hesapla
        $paymentAmount = round($matchFee / $totalPlayerCount, 2);

        // Oyuncuları maça ekle
        foreach ($playerIds as $playerId) {
            $this->participationModel->insertPlayerToMatch($matchId, $playerId, $paymentAmount);
            $this->logPlayersParticipation($matchId, $playerId, 'added');
        }

        return true;
    }

    /**
     * Bir oyuncunun maç katılımını loglar.
     *
     * @param int $matchId
     * @param int $playerId
     * @param string $action
     * @return bool
     */
    public function logPlayersParticipation(int $matchId, int $playerId, string $action): bool
    {
        $sql = "INSERT INTO match_players_log (match_id, player_id, action, action_date, action_ip) 
                VALUES (?, ?, ?, NOW(), ?)";

        $stmt = $this->participationModel->db->prepare($sql);
        return $stmt->execute([$matchId, $playerId, $action, Utils::getRealIP()]);
    }
}
