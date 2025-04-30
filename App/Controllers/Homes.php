<?php

namespace App\Controllers;
use App\Models\Home;

class Homes extends \Core\Controller
{
    public function indexAction(): void
    {
        $albums = Home::getAll();
        require "test.php";
    }
}