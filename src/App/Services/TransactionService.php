<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
  public function __construct(private Database $db) {}

  public function create(array $formData)
  {
    $sql = <<<SQL
    INSERT INTO transactions (user_id, description, amount, date)
    VALUES(:user_id, :description, :amount, :date)
    SQL;

    $params = [
      'user_id' => $_SESSION['user'],
      'description' => $formData['description'],
      'amount' => $formData['amount'],
      'date' => "{$formData['date']} 00:00:00"
    ];

    $this->db->query($sql, $params);
  }

  public function getUserTransactions(int $length, int $offset)
  {
    $searchTerm = addcslashes($_GET['s'] ?? '', '%_');

    $sql = <<<SQL
    SELECT *, DATE_FORMAT(date, '%Y-%m-%d') as formatted_date 
    FROM transactions 
    WHERE user_id = :user_id
    AND description LIKE :description
    LIMIT {$length} OFFSET {$offset}
    SQL;

    $params = [
      'user_id' => $_SESSION['user'],
      'description' => "%{$searchTerm}%"
    ];

    $transactions = $this->db->query($sql, $params)->findAll();

    return $transactions;
  }
}
