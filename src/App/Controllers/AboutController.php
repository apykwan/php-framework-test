<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class AboutController
{
  public function __construct(private TemplateEngine $view) {}

  public function about()
  {
    echo $this->view->render("/about.php");
  }
}
