<?php

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class DateFormatRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool {}

  public function getMessage(array $data, string $field, array $params): string {}
}
