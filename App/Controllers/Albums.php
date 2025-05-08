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
        //$search = $_GET['s'] ?? null; // Search for artists by name

        // if($search) {
        //     $artists = Artist::search($search);
        //     $links = LinkBuilder::artistCollectionLinks('/artists?s={search}'); // Get HATEOAS links
        // } else {
        //     $artists = Artist::getAll();
        //     $links = LinkBuilder::artistCollectionLinks(); // Get HATEOAS links
        // }
        // All albums and their artist
        $albums = Album::getAll();
        
        $links = LinkBuilder::albumCollectionLinks(); // Get HATEOAS links
        
        if (!$albums) {
            ResponseHelper::jsonError('No albums found');
            throw new \Exception('No albums found', 404);
        }

        ResponseHelper::jsonResponse($albums, $links);
    }
}