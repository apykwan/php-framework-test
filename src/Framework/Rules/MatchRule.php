<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class MatchRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    $fieldFirst = (string) $data[$field];
    $fieldSecond = (string) $data[$params[0]];
    return $fieldFirst === $fieldSecond;
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Does not match {$params[0]} field";
  }
}
