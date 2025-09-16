<?php

namespace app\models;

use core\Database;

/**
 * ParticipationModel handles the relationship between players and matches.
 * It provides methods to manage player participation in matches and track participation counts.
 */
class ParticipationModel
{
    /** @var \PDO Database connection instance */
    private $db;

    /**
     * Constructor initializes the database connection.
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Adds a player to a match with their payment amount.
     * 
     * @param int $matchId The ID of the match
     * @param int $playerId The ID of the player
     * @param float $paymentAmount The amount the player needs to pay for the match
     * @return bool True if the player was successfully added to the match
     */
    public function insertPlayerToMatch(int $matchId, int $playerId, float $paymentAmount): bool
    {
        $sql = "INSERT INTO match_players (match_id, player_id, payment_amount) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$matchId, $playerId, $paymentAmount]);
    }

    /**
     * Gets the total number of players participating in a specific match.
     * 
     * @param int $matchId The ID of the match
     * @return int The total number of players in the match
     */
    public function getMatchPlayerCount(int $matchId): int
    {
        $sql = "SELECT COUNT(*) FROM match_players WHERE match_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$matchId]);
        return (int) $stmt->fetchColumn();
    }
}
