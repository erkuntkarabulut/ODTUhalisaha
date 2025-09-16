<?php

namespace app\models;

use core\Model;

/**
 * MatchModel handles database operations related to matches.
 * It provides methods to manage matches and maintains an audit log of all match-related actions.
 */
class MatchModel extends Model
{
    /** @var string The database table name for matches */
    protected $table = "matches";

    /**
     * Retrieves all matches ordered by date (newest first).
     * 
     * @return array Array of all matches
     */
    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY match_date DESC")->fetchAll();
    }
   
    /**
     * Retrieves a specific match by its ID.
     * 
     * @param int $id The ID of the match to retrieve
     * @return array|false The match data if found, or false if not found
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE match_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Checks if a match already exists on a specific date.
     * 
     * @param string $matchDate The date to check
     * @return bool True if a match exists on the given date, false otherwise
     */
    public function isMatchExistsOnDate($matchDate)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE match_date = ?");
        $stmt->execute([$matchDate]);
        return $stmt->fetchColumn() > 0;
    }
    /**
     * Calculates the total fees for all matches.
     * 
     * This method sums up all match fees from the matches table.
     * Returns 0 if no matches exist.
     * 
     * @return float The total sum of all match fees
     */
    public function getTotalMatchFees(): float
    {
        $sql = "SELECT COALESCE(SUM(match_fee), 0) AS total_match_fees FROM matches";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return (float) $stmt->fetch()["total_match_fees"];
    }




    /**
     * Updates an existing match.
     * 
     * @param int $id The ID of the match to update
     * @param array $matchData The new match data including:
     *                         - match_date
     *                         - match_time
     *                         - location
     *                         - match_fee
     * @return bool True if the update was successful, false otherwise
     */
    public function update($id, $matchData)
    {
        $sql = "UPDATE {$this->table} SET match_date = ?, match_time = ?, location = ?, match_fee = ? WHERE match_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $matchData['match_date'],
            $matchData['match_time'],
            $matchData['location'],
            $matchData['match_fee'],
            $id
        ]);
    }

    /**
     * Deletes a match.
     * 
     * @param int $id The ID of the match to delete
     * @return bool True if the deletion was successful, false otherwise
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE match_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Adds a new match and creates an audit log entry.
     * 
     * @param array $matchData The match data including:
     *                         - match_date
     *                         - match_time
     *                         - location
     *                         - match_fee
     *                         - match_payer
     * @return int|false The ID of the newly created match, or false if the operation fails
     */
    public function add($matchData)
    {
        $sql = "INSERT INTO {$this->table} (match_date, match_time, location, match_fee, match_fee_payer) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            $matchData['match_date'],
            $matchData['match_time'],
            $matchData['location'],
            $matchData['match_fee'],
            $matchData['match_payer']
        ]);

        if ($success) {
            $matchId = $this->db->lastInsertId();
            $this->logMatchAction($matchId, $matchData, 'insert');
            return $matchId;
        }

        return false;
    }

    /**
     * Creates an audit log entry for match-related actions.
     * 
     * @param int $matchId The ID of the match
     * @param array $matchData The match data being logged
     * @param string $action The type of action (insert/update/delete)
     * @return void
     */
    private function logMatchAction($matchId, $matchData, $action)
    {
        $sql = "INSERT INTO matches_log (match_id, match_date, match_time, location, match_fee, match_fee_payer, action, action_date, action_ip) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $matchId,
            $matchData['match_date'],
            $matchData['match_time'],
            $matchData['location'],
            $matchData['match_fee'],
            $matchData['match_payer'],
            $action,
            $_SERVER['REMOTE_ADDR']
        ]);
    }    
}
