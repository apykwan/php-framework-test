<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    try {
      $next();
    } catch (ValidationException $e) {
      $oldFormData = $_POST;

      $excludedFields = ['password', 'confirmPassword'];
      $formattedFormData = array_diff_key($oldFormData, array_flip($excludedFields));

      $_SESSION['errors'] = $e->errors;

      // save the previous inputs
      $_SESSION['oldFormData'] = $formattedFormData;

      redirectTo($_SERVER['HTTP_REFERER']);
    }
  }
}
