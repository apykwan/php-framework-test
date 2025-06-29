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
      throw new ValidationException(['email' => ['Email taken']]);
    }
  }

  public function create(array $formData)
  {
    $sql = <<<SQL
    INSERT INTO users (email, age, country, social_media_url, password) 
    VALUES (:email, :age, :country, :socialMediaURL, :password)
    SQL;

    $params = [
      'email' => $formData['email'],
      'age' => $formData['age'],
      'country' => $formData['country'],
      'socialMediaURL' => $formData['socialMediaURL'],
      'password' => password_hash($formData['password'], PASSWORD_DEFAULT)
    ];

    try {
      $this->db->query($sql, $params);
      session_regenerate_id();
      $_SESSION['user'] = $this->db->id();
    } catch (\Exception $e) {
      echo "Registration failed: " . $e->getMessage();
    }
  }

  public function login(array $formData)
  {
    $sql = <<<SQL
    SELECT *
    FROM users
    WHERE email = :email
    SQL;

    $params = [
      'email' => $formData['email']
    ];

    $user = $this->db->query($sql, $params)->find();
    if (!$user || !password_verify($formData['password'], $user['password'])) {
      throw new ValidationException(['password' => ['Incorrect email or password']]);
    }

    session_regenerate_id();

    $_SESSION['user'] = $user['id'];
  }

  public function logout()
  {
    // unset($_SESSION['user']);
    session_destroy();

    // session_regenerate_id();
    $params = session_get_cookie_params();
    setcookie(
      'PHPSESSID',
      '',
      time() - 3600,
      $params['path'],
      $params['domain'],
      $params['secure'],
      $params['httponly']
    );
  }
}
