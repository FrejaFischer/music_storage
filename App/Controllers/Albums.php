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

    public function deleteAction():void
    {
        $albumID = $this->validateID($this->routeParams['album_id'] ?? null, 'Album ID');

        // Check if album has any tracks
        $albumsTracks = Album::getTracks($albumID);

        if ($albumsTracks) {
            ResponseHelper::jsonError('Album still has tracks. Album cannot be deleted');
            throw new \Exception('Album still has tracks. Album cannot be deleted', 409);
            // 409: request cannot be completed due to a conflict with the current state of the resource.
        }

        $albumIsDeleted = Album::delete($albumID);

        // If no rows were affected
        if (!$albumIsDeleted) {
            ResponseHelper::jsonError('Album not found. Nothing was deleted.');
            throw new \Exception('Album not found. Nothing was deleted.', 404);
        }

        $links = LinkBuilder::albumLinks($albumID, "/albums/$albumID", 'DELETE'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Album deleted', 'Album ID' => $albumID], $links);
    }

    public function createAction(): void
    {
        // Validate the artist ID from POST
        $this->validateID($_POST['artist_id'] ?? null, 'Artist ID');

        $result = Album::add($_POST);

        if (gettype($result) === 'array') {
            ResponseHelper::jsonError('Album not added. Validation errors: ' . $result[0]);
            throw new \Exception('Album not added. Validation errors: ' . $result[0], 400);
        }

        $links = LinkBuilder::albumLinks($result, "/albums", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Album succesfully added', 'Album ID' => $result], $links);
    }

    public function updateAction(): void
    {
        $albumID = $this->validateID($this->routeParams['album_id'] ?? null, 'Album ID');

        // Validate the artist ID from POST (if present)
        if (isset($_POST['artist_id'])) {
            $this->validateID($_POST['artist_id'] ?? null, 'Artist ID');
        }

        $result = Album::update($_POST, $albumID);

        if (gettype($result) === 'array') {
            ResponseHelper::jsonError('Album not updated. Validation errors: ' . $result[0]);
            throw new \Exception('Album not updated. Validation errors: ' . $result[0], 400);
        }

        $links = LinkBuilder::albumLinks($albumID, "/albums/$albumID", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Album succesfully updated', 'Album ID' => $albumID], $links);
    }
}