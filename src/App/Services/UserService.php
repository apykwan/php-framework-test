<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
  public function __construct(private Database $db) {}

  public function isEmailTaken(string $email)
  {
    $sql = <<<SQL
    SELECT COUNT(*) FROM users WHERE email = :email
    SQL;

    $params = [
      'email' => $email
    ];

    $emailCount = $this->db->query($sql, $params)->count();

    if ($emailCount > 0) {
      throw new ValidationException(['email' => 'Email taken']);
    }
  }
}
