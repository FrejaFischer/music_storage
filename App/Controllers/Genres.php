<?php

namespace App\Controllers;

use App\Models\Genre;
use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;

class Genres extends \Core\Controller
{
    /**
     * Getting all Genres 
     */
    public function getAction(): void
    {
        $genres = Genre::getAll();
        $links = LinkBuilder::genreLinks(); // Get HATEOAS links
        
        if (!$genres) {
            ResponseHelper::jsonError('No Genres found');
            throw new \Exception('No Genres found', 404);
        }

        ResponseHelper::jsonResponse($genres, $links);
    }
}