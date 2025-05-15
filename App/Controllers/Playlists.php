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
}