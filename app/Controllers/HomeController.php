<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\View;
// this will controle the home  page
class HomeController
{
    public function index(): View
    {
        return View::make('index');
    }
}
