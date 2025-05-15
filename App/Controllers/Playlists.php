<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;
use App\Models\Playlist;

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
            ResponseHelper::jsonError('Playlist not added. Validation errors: ' . $result[0]);
            throw new \Exception('Playlist not added. Validation errors: ' . $result[0], 400);
        }

        $links = LinkBuilder::playlistLinks($result, "/playlists", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Playlist succesfully added', 'Playlist ID' => $result], $links);
    }
}