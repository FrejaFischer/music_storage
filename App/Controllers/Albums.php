<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;
use App\Models\Album;

class Albums extends \Core\Controller
{
    /**
     * Getting all albums / All albums with title search
     */
    public function getAction(): void
    {
        $search = $_GET['s'] ?? null; // Search for albums by title

        if($search) {
            // Search for albums by title
            $albums = Album::search($search);
            $links = LinkBuilder::albumCollectionLinks('/albums?s={search}'); // Get HATEOAS links
        } else {
            // All albums and their artist
            $albums = Album::getAll();
            $links = LinkBuilder::albumCollectionLinks(); // Get HATEOAS links
        }
        
        if (!$albums) {
            ResponseHelper::jsonError('No albums found');
            throw new \Exception('No albums found', 404);
        }

        ResponseHelper::jsonResponse($albums, $links);
    }

    public function findAction(): void
    {
        $albumID = $this->validateID($this->routeParams['album_id'] ?? null, 'Album ID');

        $album = Album::get($albumID);

        if (!$album) {
            ResponseHelper::jsonError('No album found with that ID');
            throw new \Exception('No album found with that ID', 404);
        }

        $links = LinkBuilder::albumLinks($albumID); // Get HATEOAS links

        ResponseHelper::jsonResponse($album, $links);
    }

    public function trackAction(): void
    {
        $albumID = $this->validateID($this->routeParams['album_id'] ?? null, 'Album ID');

        // Check if album exist
        $album = Album::get($albumID);

        if (!$album) {
            ResponseHelper::jsonError('No album found with that ID');
            throw new \Exception('No album found with that ID', 404);
        }

        // Get albums tracks
        $albumsTracks = Album::getTracks($albumID);

        if (!$albumsTracks) {
                ResponseHelper::jsonError('No tracks found for that album ID');
                throw new \Exception('No tracks found for that album ID', 404);
        }

        $links = LinkBuilder::albumLinks($albumID, "/albums/$albumID/tracks"); // Get HATEOAS links

        ResponseHelper::jsonResponse($albumsTracks, $links);

    }
}