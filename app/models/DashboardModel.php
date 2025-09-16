<?php

namespace app\models;

use core\Model;

/**
 * DashboardModel handles database operations related to the dashboard functionality.
 * It provides methods to calculate financial statistics and player balances.
 */
class DashboardModel extends Model
{
    /**
     * Calculates the total amount of all payments made by players.
     * 
     * @return float The total sum of all payments
     */
    public function getTotalPayments(): float
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) AS total_payments FROM payments");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Calculates the total amount of all match fees.
     * 
     * @return float The total sum of all match fees
     */
    public function getTotalMatchFees(): float
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(match_fee), 0) AS total_match_fees FROM matches");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Retrieves all players with their calculated balances.
     * 
     * The balance is calculated as:
     * Balance = Total Payments - Total Match Fees
     * 
     * @return array Array of players with their IDs, names, and balances
     */
    public function getAllPlayersWithBalance(): array
    {
        $stmt = $this->db->query("
SELECT
    player_id, 
    name,
    (COALESCE(payment_total, 0) -COALESCE(match_total, 0)  ) AS balance
FROM (
    SELECT p.name,p.player_id, 
           (SELECT SUM(mp.payment_amount)
            FROM match_players mp
            WHERE mp.player_id = p.player_id) AS match_total,
           (SELECT SUM(payments.amount)
            FROM payments
            WHERE payments.player_id = p.player_id) AS payment_total
    FROM players p
) AS combined;

;

        ");
        return $stmt->fetchAll();
    }
    /**
     * Retrieves players who have a negative balance.
     * 
     * A negative balance means the player owes money:
     * (Total Payments - Total Match Fees) < 0
     * 
     * @return array Array of players with negative balances, including their IDs, names, and balances
     */
    public function getNegativeBalancePlayers(): array
    {
        $stmt = $this->db->query("
SELECT
    player_id,
    name,
    (COALESCE(payment_total, 0) - COALESCE(match_total, 0)) AS balance
FROM (
    SELECT p.name, p.player_id,
           (SELECT SUM(mp.payment_amount)
            FROM match_players mp
            WHERE mp.player_id = p.player_id) AS match_total,
           (SELECT SUM(payments.amount)
            FROM payments
            WHERE payments.player_id = p.player_id) AS payment_total
    FROM players p
) AS combined
WHERE (COALESCE(payment_total, 0) - COALESCE(match_total, 0)) < 0;
        ");
        return $stmt->fetchAll();
    }
}
