<?php

/**
 * PHP MySQL Transaction Demo
 */
class TransactionDemo {

    const DB_HOST = 'localhost';
    const DB_NAME = 'create_management';
    const DB_USER = 'root';
    const DB_PASSWORD = '';

    /**
     * PDO instance
     * @var PDO 
     */
    private $pdo = null;

    /**
     * Transfer money between two accounts
     * @param int $from
     * @param int $to
     * @param float $amount
     * @return true on success or false on failure.
     */
    public function transfer($from_account, $to_account, $transfer_amount) {

        try {
            $this->pdo->beginTransaction();

            // get available amount of the transferer account
            $sql = 'SELECT current_credit FROM users WHERE accout_no=:from_account';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(":from_account" => $from_account));
            $availableAmount = (int) $stmt->fetchColumn();
            $stmt->closeCursor();

            if ($availableAmount < $transfer_amount) {
                echo 'Insufficient amount to transfer';
                return false;
            }
            // deduct from the transferred account
            $sql_update_from = 'UPDATE users
				SET current_credit = current_credit - :transfer_amount
				WHERE accout_no = :from_account';
            $stmt = $this->pdo->prepare($sql_update_from);
            $stmt->execute(array(":from_account" => $from_account, ":transfer_amount" => $transfer_amount));
            $stmt->closeCursor();

            // add to the receiving account
            $sql_update_to = 'UPDATE users
                                SET current_credit = current_credit + :transfer_amount
                                WHERE accout_no = :to_account';
            $stmt = $this->pdo->prepare($sql_update_to);
            $stmt->execute(array(":to_account" => $to_account, ":transfer_amount" => $trnsfer_amount));

            // commit the transaction
            $this->pdo->commit();

            echo 'The amount has been transferred successfully';

            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            die($e->getMessage());
        }
    }

    /**
     * Open the database connection
     */
    public function __construct() {
        // open database connection
        $conStr = sprintf("mysql:host=%s;dbname=%s", self::DB_HOST, self::DB_NAME);
        try {
            $this->pdo = new PDO($conStr, self::DB_USER, self::DB_PASSWORD);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * close the database connection
     */
    public function __destruct() {
        // close the database connection
        $this->pdo = null;
    }

}

// test the transfer method
$obj = new TransactionDemo();

// transfer 30K from from account 1 to 2
$obj->transfer(1, 2, 30000);


// transfer 5K from from account 1 to 2
$obj->transfer(1, 2, 5000);