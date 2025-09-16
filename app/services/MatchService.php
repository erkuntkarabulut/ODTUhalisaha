<?php

namespace app\services;

use app\models\MatchModel;
use app\models\ParticipationModel;
use app\models\MatchPlayerModel;


class MatchService
{
    private $matchModel;
    private $participationModel;
    private $matchPlayerModel; // Doğru isimlendirme


    public function __construct()
    {
        $this->matchModel = new MatchModel();
        $this->participationModel = new ParticipationModel();
        $this->matchPlayerModel = new MatchPlayerModel(); // Doğru değişken adı


    }

    /**
     * Tüm maçları getir
     */
    public function getAllMatches()
    {
        return $this->matchModel->getAll();
    }

    /**
     * ID'ye göre maçı getir
     */
    public function getMatchById($matchId)
    {
        return $this->matchModel->getById($matchId);
    }

    /**
     * Yeni bir maç ekle
     */
    public function addMatch($matchData)
    {
        if ($this->matchModel->isMatchExistsOnDate($matchData['match_date'])) {
            return ['success' => false, 'message' => 'Aynı tarihte zaten bir maç var.'];
        }

        $matchId = $this->matchModel->add($matchData);
        if (!$matchId) {
            return ['success' => false, 'message' => 'Maç eklenirken bir hata oluştu.'];
        }

        // Oyuncuları maça ekle
        $this->participationModel->addPlayersToMatch($matchId, $matchData['players']);

        return ['success' => true, 'message' => 'Maç ve oyuncular başarıyla eklendi.'];
    }

    /**
     * Maçı güncelle
     */
    public function updateMatch($matchId, $matchData)
    {
        return $this->matchModel->update($matchId, $matchData);
    }

    /**
     * Maçı sil
     */
    public function deleteMatch($matchId)
    {
        return $this->matchModel->delete($matchId);
    }
    /**
     * Yeni maç ekler ve oyuncuları maça ekler.
     * @param string $match_date
     * @param string $match_time
     * @param string $location
     * @param float $match_fee
     * @param int $match_fee_payer
     * @param array $player_ids
     * @return bool
     */
    public function addMatchWithPlayers($match_date, $match_time, $location, $match_fee, $match_fee_payer, $player_ids)
    {
        $matchData = [
            'match_date' => $match_date,
            'match_time' => $match_time,
            'location' => $location,
            'match_fee' => $match_fee,
            'match_payer' => $match_fee_payer
        ];

        // addMatch() yerine add() metodu çağrılıyor
        $match_id = $this->matchModel->add($matchData);
        
        if ($match_id) {
            // Oyuncu sayısını al
            $playerCount = count($player_ids);
    
            // Oyuncu sayısı sıfır olamaz, bölme hatasını önlemek için kontrol ekledik
            if ($playerCount > 0) {
                // Kişi başı maç ücreti hesaplanıyor
                $matchFeeOnePlayer = round($match_fee / $playerCount, 2);
            } else {
                $matchFeeOnePlayer = 0;
            }
    
            // Oyuncuları maça ekle ve kişi başı tutarı ver
            return $this->matchPlayerModel->addPlayersToMatch($match_id, $player_ids, $matchFeeOnePlayer);
        }

        return false;
    }
}
