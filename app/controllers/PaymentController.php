<?php

namespace app\controllers;

use app\services\PaymentService;
use core\Controller;

/**
 * PaymentController handles all payment-related operations in the application.
 * It manages the creation, listing, updating, and deletion of player payments.
 */
class PaymentController extends Controller
{
    /** @var PaymentService Service class that handles payment-related business logic */
    private $paymentService;

    /**
     * Constructor initializes the PaymentController with a new PaymentService instance.
     */
    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    /**
     * Displays the payment listing page with all payments and sorted player information.
     * 
     * @return void
     */
    public function index()
    {
        $payments = $this->paymentService->getAllPayments();
        $getSortedPlayers = $this->paymentService->getSortedPlayers();
        $this->view('payment/index', ['payments' => $payments,'players' => $getSortedPlayers]);
    }

    /**
     * Handles the creation of a new payment.
     * 
     * This method processes POST requests to create a new payment with:
     * - Player ID
     * - Payment amount
     * - Payment date
     * 
     * @return void
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->paymentService->addPayment($_POST['player_id'], $_POST['amount'], $_POST['payment_date']);
            header("Location: /halisaha/index.php?controller=payment&action=index&success=Ödeme%20eklendi.");
            exit();
        }
    }

    /**
     * Handles the updating of an existing payment.
     * 
     * This method processes POST requests to update payment details including:
     * - Payment ID
     * - Player ID
     * - Amount
     * - Payment date
     * 
     * @return void
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
            $result = $this->paymentService->updatePayment($_POST['payment_id'], $_POST['player_id'],$_POST['amount'], $_POST['payment_date']);
    
            if ($result) {
                $message = "Ödeme başarıyla güncellendi.";
                header("Location: /halisaha/index.php?controller=payment&action=index&success=" . urlencode($message));
            } else {
                $message = "Ödeme güncellenirken bir hata oluştu.";
                header("Location: /halisaha/index.php?controller=payment&action=index&error=" . urlencode($message));
            }
            exit();
        }
    }

    /**
     * Handles the deletion of a payment.
     * 
     * This method:
     * 1. Retrieves the payment ID from the GET parameters
     * 2. Deletes the specified payment if it exists
     * 3. Redirects back to the payment listing page with appropriate success/error message
     * 
     * @return void
     */
    public function delete()
    {
        if (isset($_GET['id'])) {
            $result = $this->paymentService->deletePayment($_GET['id']);

            if ($result === true) {
                header("Location: /halisaha/index.php?controller=payment&action=index&success=Ödeme%20silindi.");
            } else {
                header("Location: /halisaha/index.php?controller=payment&action=index&error=" . urlencode($result));
            }
            exit();
        }
    }
}
