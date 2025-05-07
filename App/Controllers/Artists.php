<?php

namespace App\Controllers;
use App\Models\Artist;
use Core\ResponseHelper;

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
            ResponseHelper::jsonError('No artists found');
            throw new \Exception('No artists found', 404);
        }

        ResponseHelper::jsonResponse($artists);
    }

    public function findAction(): void
    {
        $artistID = $this->routeParams['artist_id'] ?? null;

        // Check if there is an id in the route path
        if (!$artistID) {
            ResponseHelper::jsonError('Missing artist ID');
            throw new \Exception('Missing artist ID', 400);
            return;
        }
        // Check if the id is numeric
        if (!ctype_digit($artistID)) {
            throw new \Exception('Invalid artist ID format', 400);
            return;
        }

        $artist = Artist::get($artistID);

        if (!$artist) {
            ResponseHelper::jsonError('No artist found with that ID');
            throw new \Exception('No artist found with that ID', 404);
        }

        ResponseHelper::jsonResponse($artist);
    }

}