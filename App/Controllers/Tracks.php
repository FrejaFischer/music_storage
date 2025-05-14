<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;
use App\Models\Track;

class Tracks extends \Core\Controller
{
    /**
    * Getting all tracks with name search
    */
    public function getAction(): void 
    {
        $search = $_GET['s'] ?? null; // Search for tracks by name

        if($search) {
            $tracks = Track::search($search);
            $links = LinkBuilder::trackCollectionLinks('/tracks?s={search}'); // Get HATEOAS links
        } else {
            ResponseHelper::jsonError('No search text found. Please search for track name in this format: /tracks?s=<search_text>');
            throw new \Exception('No search text found. Please search for track name in this format: /tracks?s=<search_text>', 404);
        }
        
        if (!$tracks) {
            ResponseHelper::jsonError('No tracks found');
            throw new \Exception('No tracks found', 404);
        }

        ResponseHelper::jsonResponse($tracks, $links);
    }
}