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
            $links = LinkBuilder::albumCollectionLinks('/albums'); // Get HATEOAS links
        }
        
        if (!$albums) {
            ResponseHelper::jsonError('No albums found');
            throw new \Exception('No albums found', 404);
        }

        ResponseHelper::jsonResponse($albums, $links);
    }

    /**
     * Getting an album by id
     */
    public function findAction(): void
    {
        $albumID = $this->validateID($this->routeParams['album_id'] ?? null, 'Album ID');

        $album = Album::get($albumID);

        if (!$album) {
            ResponseHelper::jsonError('No album found with that ID');
            throw new \Exception('No album found with that ID', 404);
        }

        $links = LinkBuilder::albumLinks($albumID, "/albums/$albumID"); // Get HATEOAS links

        ResponseHelper::jsonResponse($album, $links);
    }

    /**
     * Gets all track from an Album
     */
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

    /**
     * Delete an album
     */
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

    /**
     * Create new album
     */
    public function createAction(): void
    {
        $result = Album::add($_POST);

        if (gettype($result) === 'array') {
             $validationMessage = '';

            // loop over all validation errors to combine them in string
            foreach ($result as $message) {
                $validationMessage .= $message . ' ';
            }

            ResponseHelper::jsonError('Album not added, because of validation errors', $result);
            throw new \Exception('Album not added. Validation errors: ' . $validationMessage, 400);
        }

        $links = LinkBuilder::albumLinks($result, "/albums", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Album succesfully added', 'Album ID' => $result], $links);
    }

    /**
     * Update album
     */
    public function updateAction(): void
    {
        $albumID = $this->validateID($this->routeParams['album_id'] ?? null, 'Album ID');

        $result = Album::update($_POST, $albumID);

        if (gettype($result) === 'array') {
            $validationMessage = '';

            // loop over all validation errors to combine them in string
            foreach ($result as $message) {
                $validationMessage .= $message . ' ';
            }

            ResponseHelper::jsonError('Album not updated, because of validation errors', $result);
            throw new \Exception('Album not updated. Validation errors: ' . $validationMessage, 400);
        } else if ($result === false) {
            // If no rows were affected
            ResponseHelper::jsonError('Album not found. Nothing was updated.');
            throw new \Exception('Album not found. Nothing was updated.', 404);
        }

        $links = LinkBuilder::albumLinks($albumID, "/albums/$albumID", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Album succesfully updated', 'Album ID' => $albumID], $links);
    }
}