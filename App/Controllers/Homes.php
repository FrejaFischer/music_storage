<?php

namespace App\Controllers;
use App\Models\Home;

class Homes extends \Core\Controller
{
    public function indexAction(): void
    {
        $albums = Home::getAll();
        
        if (!$albums) {
            $this->jsonError('No albums found', 404);
        }

        $this->jsonResponse($albums);
    }
}