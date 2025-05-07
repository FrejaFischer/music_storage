<?php

namespace App\Controllers;

use App\Models\Artist;
use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;

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
            $links = LinkBuilder::artistCollectionLinks('/artists?s={search}'); // Get HATEOAS links
        } else {
            $artists = Artist::getAll();
            $links = LinkBuilder::artistCollectionLinks(); // Get HATEOAS links
        }
        
        if (!$artists) {
            ResponseHelper::jsonError('No artists found');
            throw new \Exception('No artists found', 404);
        }

        ResponseHelper::jsonResponse($artists, $links);
    }

    public function findAction(): void
    {
        $artistID = $this->validateID($this->routeParams['artist_id'] ?? null, 'Artist ID');

        $artist = Artist::get($artistID);

        if (!$artist) {
            ResponseHelper::jsonError('No artist found with that ID');
            throw new \Exception('No artist found with that ID', 404);
        }

        $links = LinkBuilder::artistLinks($artistID); // Get HATEOAS links

        ResponseHelper::jsonResponse($artist, $links);
    }

    public function albumAction(): void
    {
        $artistID = $this->validateID($this->routeParams['artist_id'] ?? null, 'Artist ID');

        // Check if artist exist
        $artist = Artist::get($artistID);

        if (!$artist) {
            ResponseHelper::jsonError('No artist found with that ID');
            throw new \Exception('No artist found with that ID', 404);
        }

        // Get artists albums
        $artistsAlbums = Artist::getAlbums($artistID);

        if (!$artistsAlbums) {
                ResponseHelper::jsonError('No albums found for that artist ID');
                throw new \Exception('No artist found with that ID', 404);
        }

        $links = LinkBuilder::artistLinks($artistID, "/artists/$artistID/albums"); // Get HATEOAS links

        ResponseHelper::jsonResponse($artistsAlbums, $links);

    }

}