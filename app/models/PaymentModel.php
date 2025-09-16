<?php

namespace app\models;

use core\Model;

/**
 * PaymentModel handles database operations related to player payments.
 * It provides methods to manage payments and maintains an audit log of all payment-related actions.
 */
class PaymentModel extends Model
{
    /** @var string The database table name for payments */
    protected $table = "payments";

    /**
     * Retrieves all payments with associated player information.
     * 
     * @return array Array of payments with player details, ordered by payment date (newest first)
     */
    public function getAllPayments()
    {
        return $this->db->query("
            SELECT p.payment_id, p.player_id, pl.name AS player_name, p.amount, p.payment_date 
            FROM {$this->table} p
            JOIN players pl ON p.player_id = pl.player_id
            ORDER BY p.payment_date DESC
        ")->fetchAll();
    }

    /**
     * Adds a new payment and creates an audit log entry.
     * 
     * @param int $player_id The ID of the player making the payment
     * @param float $amount The payment amount
     * @param string $payment_date The date of the payment
     * @return int|false The ID of the newly created payment, or false if the operation fails
     */
    public function addPayment($player_id, $amount, $payment_date)
    {
        $sql = "INSERT INTO {$this->table} (player_id, amount, payment_date) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$player_id, $amount, $payment_date])) {
            $paymentId = $this->db->lastInsertId();
            $this->logAction($paymentId,$player_id, $amount, $payment_date, 'insert');
            return $paymentId;
        }
        return false;
    }

    /**
     * Retrieves a specific payment by its ID.
     * 
     * @param int $payment_id The ID of the payment to retrieve
     * @return array|false The payment data if found, or false if not found
     */
    public function getPaymentById($payment_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE payment_id = ?");
        $stmt->execute([$payment_id]);
        return $stmt->fetch();
    }

    /**
     * Updates an existing payment and creates an audit log entry.
     * 
     * @param int $payment_id The ID of the payment to update
     * @param int $player_id The ID of the player
     * @param float $amount The new payment amount
     * @param string $payment_date The new payment date
     * @return bool True if the update was successful, false otherwise
     */
    public function updatePayment($payment_id,$player_id, $amount, $payment_date)
    {
        $sql = "UPDATE {$this->table} SET amount = ?, payment_date = ? WHERE payment_id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$amount, $payment_date, $payment_id])) {
            $this->logAction($payment_id, $player_id, $amount, $payment_date,'update');
            return true;
        }
        return false;
    }

    /**
     * Deletes a payment and creates an audit log entry.
     * 
     * @param int $payment_id The ID of the payment to delete
     * @param int $player_id The ID of the player
     * @param float $amount The amount of the payment being deleted
     * @param string $payment_date The date of the payment being deleted
     * @return bool|string True if deletion was successful, error message if it failed
     */
    public function deletePayment($payment_id,$player_id, $amount, $payment_date)
    {
        $sql = "DELETE FROM {$this->table} WHERE payment_id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$payment_id])) {
            $this->logAction($payment_id,$player_id, $amount, $payment_date, 'delete');
            return true;
        }
        return "Silme işlemi sırasında bir hata oluştu.";
    }

    /**
     * Creates an audit log entry for payment-related actions.
     * 
     * @param int $paymentId The ID of the payment
     * @param int $player_id The ID of the player
     * @param float $amount The payment amount
     * @param string $payment_date The payment date
     * @param string $action The type of action (insert/update/delete)
     * @return void
     */
    private function logAction($paymentId, $player_id, $amount, $payment_date, $action)
    {
        $sql = "INSERT INTO payments_log (payment_id, player_id, amount, payment_date, action, action_date, action_ip) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$paymentId, $player_id, $amount, $payment_date, $action, $_SERVER['REMOTE_ADDR']]);
    }
}
