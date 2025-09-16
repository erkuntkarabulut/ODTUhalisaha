<?php

namespace app\services;

use app\models\PaymentModel;
use app\models\PlayerModel;

class PaymentService
{
    private $paymentModel;
    private $playerModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->playerModel = new PlayerModel();
    }

    public function getAllPayments()
    {
        return $this->paymentModel->getAllPayments();
    }
    public function getSortedPlayers(): array
    {
        $players = $this->playerModel->getAll();
    
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

    public function addPayment($player_id, $amount, $payment_date)
    {
        return $this->paymentModel->addPayment($player_id, $amount, $payment_date);
    }

    public function getPaymentById($payment_id)
    {
        return $this->paymentModel->getPaymentById($payment_id);
    }

    public function updatePayment($payment_id, $player_id,$amount, $payment_date)
    {
        return $this->paymentModel->updatePayment($payment_id, $player_id,$amount, $payment_date);
    }

    public function deletePayment($payment_id,$player_id, $amount, $payment_date)
    {
        return $this->paymentModel->deletePayment($payment_id,$player_id, $amount, $payment_date);
    }
}
