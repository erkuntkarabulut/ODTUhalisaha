<?php

namespace app\models;

use core\Model;

/**
 * PlayerModel handles database operations related to players.
 * It provides methods to manage players and maintains an audit log of all player-related actions.
 */
class PlayerModel extends Model
{
    /** @var string The database table name for players */
    protected $table = "players";

    /**
     * Retrieves all players ordered alphabetically by name.
     * 
     * @return array Array of all players
     */
    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY name ASC")->fetchAll();
    }

    /**
     * Adds a new player and creates an audit log entry.
     * 
     * @param string $name The player's name
     * @param string $email The player's email address
     * @param string $phone_number The player's phone number
     * @return int|false The ID of the newly created player, or false if the operation fails
     */
    public function addPlayer($name, $email, $phone_number)
    {
        $sql = "INSERT INTO {$this->table} (name, email, phone_number) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$name, $email, $phone_number])) {
            $playerId = $this->db->lastInsertId();
            $this->logAction($playerId, 'insert');
            return $playerId;
        }
        return false;
    }

    /**
     * Retrieves a specific player by their ID.
     * 
     * @param int $id The ID of the player to retrieve
     * @return array|false The player data if found, or false if not found
     */
    public function getPlayerById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE player_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Updates an existing player and creates an audit log entry.
     * 
     * @param int $id The ID of the player to update
     * @param string $name The player's new name
     * @param string $email The player's new email address
     * @param string $phone_number The player's new phone number
     * @return bool True if the update was successful, false otherwise
     */
    public function updatePlayer($id, $name, $email, $phone_number)
    {
        $sql = "UPDATE {$this->table} SET name = ?, email = ?, phone_number = ? WHERE player_id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$name, $email, $phone_number, $id])) {
            $this->logAction($id, 'update');
            return true;
        }
        return false;
    }

    /**
     * Deletes a player and creates an audit log entry.
     * 
     * This method includes safety checks:
     * 1. Verifies that the player is not participating in any matches
     * 2. Only allows deletion if the player has no match history
     * 
     * @param int $id The ID of the player to delete
     * @return bool|string True if deletion was successful, error message if it failed
     */
    public function deletePlayer($id)
    {
        // Önce oyuncunun herhangi bir maç kaydı olup olmadığını kontrol et
        $query = "SELECT COUNT(*) FROM match_players WHERE player_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return "Bu oyuncu maçlara katılmış, silemezsiniz! Öncelikle bu oyuncuyu maçlardan kaldırın.";
        }

        // Eğer bağlantısı yoksa silebiliriz
        $sql = "DELETE FROM players WHERE player_id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$id])) {
            $this->logAction($id, 'delete');
            return true;
        }
        return "Silme işlemi sırasında bir hata oluştu.";
    }

    /**
     * Creates an audit log entry for player-related actions.
     * 
     * @param int $playerId The ID of the player
     * @param string $action The type of action (insert/update/delete)
     * @return void
     */
    private function logAction($playerId, $action)
    {
        $sql = "INSERT INTO players_log (player_id, action, action_date, action_ip) VALUES (?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$playerId, $action, $_SERVER['REMOTE_ADDR']]);
    }
}
