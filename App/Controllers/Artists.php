<?php

namespace App\Controllers;
use App\Models\Artist;

class Artists extends \Core\Controller
{
    /**
     * Getting all artists / All artists with name search
     */
    public function getAction(): void
    {
        $search = $_GET['s'] ?? null; // Search for artists by name

        if($search) {
            $artists = Artist::search($search);
        } else {
            $artists = Artist::getAll();
        }
        
        if (!$artists) {
            $this->jsonError('No artists found', 404);
        }

        $this->jsonResponse($artists);
    }

}