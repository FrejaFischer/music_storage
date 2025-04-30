<?php

namespace App\Controllers;
use App\Models\Artist;

class Artists extends \Core\Controller
{
    /**
     * Getting all artist
     */
    public function getAction(): void
    {
        $artists = Artist::getAll();
        
        if (!$artists) {
            $this->jsonError('No artists found', 404);
        }

        $this->jsonResponse($artists);
    }
}