<?php

namespace app\models;

use core\Model;

/**
 * MatchPlayerModel handles the relationship between matches and players.
 * It manages the assignment of players to matches and maintains an audit log of these assignments.
 */
class MatchPlayerModel extends Model {

    /** @var string The database table name for match-player relationships */
    protected $table = "match_players";

    /**
     * Adds players to a match and creates audit log entries.
     * 
     * This method:
     * 1. Inserts each player into the match_players table
     * 2. Creates an audit log entry for each player addition
     * 
     * @param int $match_id The ID of the match
     * @param array $player_ids Array of player IDs to add to the match
     * @param float $payment_amount The payment amount for each player
     * @return bool True if all players were successfully added
     */
    public function addPlayersToMatch($match_id, array $player_ids,$payment_amount)
    {
        $sql = "INSERT INTO {$this->table} (match_id, player_id, payment_amount) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        foreach ($player_ids as $player_id) {
            if ($stmt->execute([$match_id, $player_id,$payment_amount])) {
                $match_player_id = $this->db->lastInsertId();
                $this->logMatchPlayerAction($match_player_id, $match_id, $player_id, $payment_amount, 'insert');
            }
        }
        return true;
    }

    /**
     * Creates an audit log entry for match-player actions.
     * 
     * @param int $match_player_id The ID of the match-player relationship
     * @param int $match_id The ID of the match
     * @param int $player_id The ID of the player
     * @param float $payment_amount The payment amount
     * @param string $action The type of action (insert/update/delete)
     * @return void
     */
    private function logMatchPlayerAction($match_player_id, $match_id, $player_id, $payment_amount, $action)
    {
        $sql = "INSERT INTO match_players_log (match_player_id, match_id, player_id, payment_amount, action, action_date, action_ip) 
                VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$match_player_id, $match_id, $player_id, $payment_amount, $action, $_SERVER['REMOTE_ADDR']]);
    }
}

