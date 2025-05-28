<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;
use App\Models\Playlist;
use App\Models\Track;

class Playlists extends \Core\Controller
{
    /**
     * Getting all platlists / All playlists with name search
     */
    public function getAction(): void
    {
        $search = $_GET['s'] ?? null; // Search for playlists by name

        if($search) {
            // Search for playlist by name
            $playlists = Playlist::search($search);
            $links = LinkBuilder::playlistCollectionLinks('/playlists?s={search}'); // Get HATEOAS links
        } else {
            // All playlists
            $playlists = Playlist::getAll();
            $links = LinkBuilder::playlistCollectionLinks(); // Get HATEOAS links
        }
        
        if (!$playlists) {
            ResponseHelper::jsonError('No playlists found');
            throw new \Exception('No playlists found', 404);
        }

        ResponseHelper::jsonResponse($playlists, $links);
    }

    /**
     * Finding track by id
     */
    public function findAction(): void
    {
        $playlistID = $this->validateID($this->routeParams['playlist_id'] ?? null, 'Playlist ID');

        $playlist = Playlist::get($playlistID);

        if (!$playlist) {
            ResponseHelper::jsonError('No playlist found with that ID');
            throw new \Exception('No playlist found with that ID', 404);
        }

        // Find tracks on playlist
        $playlistsTracks = Playlist::getTracks($playlistID);

        if (!$playlistsTracks) {
            $playlist[0]['Tracks'] = 'No tracks connected to playlist';
        } else {
            $playlist[0]['Tracks'] = $playlistsTracks;
        }

        $links = LinkBuilder::playlistLinks($playlistID); // Get HATEOAS links

        ResponseHelper::jsonResponse($playlist, $links);
    }

    /**
     * Creating new playlist
     */
    public function createAction(): void
    {
        $result = Playlist::add($_POST);

        if (gettype($result) === 'array') {
            $validationMessage = '';

            // loop over all validation errors to combine them in string
            foreach ($result as $message) {
                $validationMessage .= $message . ' ';
            }

            ResponseHelper::jsonError('Playlist not added, because of validation errors', $result);
            throw new \Exception('Playlist not added. Validation errors: ' . $validationMessage, 400);
        }

        $links = LinkBuilder::playlistLinks($result, "/playlists", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Playlist succesfully added', 'Playlist ID' => $result], $links);
    }

    /**
     * Assigns a track to playlist
     */
    public function trackAddAction(): void
    {
        $playlistID = $this->validateID($this->routeParams['playlist_id'] ?? null, 'Playlist ID');

        // Check if playlist exist
        $playlist = Playlist::get($playlistID);

        if (!$playlist) {
            ResponseHelper::jsonError('No playlist found with that ID');
            throw new \Exception('No playlist found with that ID', 404);
        }

        $result = Playlist::addTrack($_POST, $playlistID);

        if (gettype($result) === 'array') {
            $validationMessage = '';

            // loop over all validation errors to combine them in string
            foreach ($result as $message) {
                $validationMessage .= $message . ' ';
            }

            ResponseHelper::jsonError('Track not assigned, because of validation errors', $result);
            throw new \Exception('Track not assigned. Validation errors: ' . $validationMessage, 400);
        }

        $links = LinkBuilder::playlistLinks($playlistID, "/playlists/$playlistID/tracks", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Track succesfully assigned'], $links);
    }

    /**
     * Removes a track from a playlist
     */
    public function trackRemoveAction(): void
    {
        $playlistID = $this->validateID($this->routeParams['playlist_id'] ?? null, 'Playlist ID');
        $trackID = $this->validateID($this->routeParams['track_id'] ?? null, 'Track ID');

        // Check if playlist exist
        $playlist = Playlist::get($playlistID);
        if (!$playlist) {
            ResponseHelper::jsonError('No playlist found with that ID');
            throw new \Exception('No playlist found with that ID', 404);
        }
        
        // Check if track exist
        $track = Track::get($trackID);
        if (!$track) {
            ResponseHelper::jsonError('No track found with that ID');
            throw new \Exception('No track found with that ID', 404);
        }

        $trackIsRemoved = Playlist::removeTrack($playlistID, $trackID);

        // If no rows were affected
        if (!$trackIsRemoved) {
            ResponseHelper::jsonError('The track do not have an existing connection to the playlist. Track can therefor not be removed.');
            throw new \Exception('The track do not have an existing connection to the playlist. Track can therefor not be removed.', 404);
        }

        $links = LinkBuilder::playlistLinks($playlistID, "/playlists/$playlistID/tracks/$trackID", 'DELETE', $trackID); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Track succesfully removed'], $links);
    }

    /**
     * Deleting a playlist
     */
    public function deleteAction():void
    {
        $playlistID = $this->validateID($this->routeParams['playlist_id'] ?? null, 'Playlist ID');

        // Check if there are any tracks connected to the playlist
        $tracks = Playlist::getTracks($playlistID);

        if ($tracks) {
            ResponseHelper::jsonError('Tracks is still connected to the playlist. Playlist cannot be deleted');
            throw new \Exception('Tracks is still connected to the playlist. Playlist cannot be deleted', 409);
            // 409: request cannot be completed due to a conflict with the current state of the resource.
        }

        $playlistIsDeleted = Playlist::delete($playlistID);

        // If no rows were affected
        if (!$playlistIsDeleted) {
            ResponseHelper::jsonError('Playlist not found. Nothing was deleted.');
            throw new \Exception('Playlist not found. Nothing was deleted.', 404);
        }

        $links = LinkBuilder::playlistLinks($playlistID, "/playlists/$playlistID", 'DELETE'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Playlist deleted', 'Playlist ID' => $playlistID], $links);
    }
}