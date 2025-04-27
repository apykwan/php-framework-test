<?php

declare(strict_types=1);

function dd(mixed $value)
{
  echo '<pre>';
  print_r($value);
  echo '</pre>';
  die();
}

function e($value): string
{
  return htmlspecialchars($value);
}
