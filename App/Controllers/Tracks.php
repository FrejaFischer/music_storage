<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;
use App\Models\Track;

class Tracks extends \Core\Controller
{
    /**
    * Getting all tracks with name search or composer search
    */
    public function getAction(): void 
    {
        $search = $_GET['s'] ?? null; // Search for tracks by name
        $composer = $_GET['composer'] ?? null; // Search for tracks by composer

        if($search) {
            $tracks = Track::search($search, "Name");
            $links = LinkBuilder::trackCollectionLinks('/tracks?s={search}'); // Get HATEOAS links
        } else if($composer) {
            $tracks = Track::search($composer, "Composer");
            $links = LinkBuilder::trackCollectionLinks('/tracks?composer={search}');
        } else {
            ResponseHelper::jsonError('No search text found. Please search for track name or composer');
            throw new \Exception('No search text found. Please search for track name or composer', 404);
        }
        
        if (!$tracks) {
            ResponseHelper::jsonError('No tracks found');
            throw new \Exception('No tracks found', 404);
        }

        ResponseHelper::jsonResponse($tracks, $links);
    }

    /**
     * Finding track by id
     */
    public function findAction(): void
    {
        $trackID = $this->validateID($this->routeParams['track_id'] ?? null, 'Track ID');

        $track = Track::get($trackID);

        if (!$track) {
            ResponseHelper::jsonError('No track found with that ID');
            throw new \Exception('No track found with that ID', 404);
        }

        $links = LinkBuilder::trackLinks($trackID); // Get HATEOAS links

        ResponseHelper::jsonResponse($track, $links);
    }

    /**
     * Creating new track
     */
    public function createAction(): void
    {
        $result = Track::add($_POST);

        if (gettype($result) === 'array') {
            $validationMessage = '';

            // loop over all validation errors to combine them in string
            foreach ($result as $message) {
                $validationMessage .= $message . ' ';
            }

            ResponseHelper::jsonError("Track not added, because of validation errors", $result);
            throw new \Exception("Track not added. Validation errors: $validationMessage", 400);

        }

        $links = LinkBuilder::trackLinks($result, "/tracks", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Track succesfully added', 'Track ID' => $result], $links);
    }

    /**
     * Updating track
     */
    public function updateAction(): void
    {
        $trackID = $this->validateID($this->routeParams['track_id'] ?? null, 'Track ID');

        $result = Track::update($_POST, $trackID);

        if (gettype($result) === 'array') {
            $validationMessage = '';

            // loop over all validation errors to combine them in string
            foreach ($result as $message) {
                $validationMessage .= $message . ' ';
            }

            ResponseHelper::jsonError("Track not updated, because of validation errors", $result);
            throw new \Exception("Track not updated. Validation errors: $validationMessage", 400);
        }

        $links = LinkBuilder::trackLinks($trackID, "/tracks/$trackID", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Track succesfully updated', 'Track ID' => $trackID], $links);
    }

    /**
     * Deleting a track
     */
    public function deleteAction():void
    {
        $trackID = $this->validateID($this->routeParams['track_id'] ?? null, 'Track ID');

        // Check if track belong to a playlist
        $hasConnections = Track::isConnectedToPlaylist($trackID);

        if ($hasConnections) {
            ResponseHelper::jsonError('Track is still connected to one or many playlists. Track cannot be deleted');
            throw new \Exception('Track is still connected to one or many playlists. Track cannot be deleted', 409);
            // 409: request cannot be completed due to a conflict with the current state of the resource.
        }

        $trackIsDeleted = Track::delete($trackID);

        // If no rows were affected
        if (!$trackIsDeleted) {
            ResponseHelper::jsonError('Track not found. Nothing was deleted.');
            throw new \Exception('Track not found. Nothing was deleted.', 404);
        }

        $links = LinkBuilder::trackLinks($trackID, "/tracks/$trackID", 'DELETE'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Track deleted', 'Track ID' => $trackID], $links);
    }
}