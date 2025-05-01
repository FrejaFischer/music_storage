<?php

namespace App\Controllers;
use App\Models\Home;
use Core\ResponseHelper;

class Homes extends \Core\Controller
{
    public function indexAction(): void
    {
        $albums = Home::getAll();
        
        if (!$albums) {
            throw new \Exception('No albums found', 404);
        }

        ResponseHelper::jsonResponse($albums);
    }
}