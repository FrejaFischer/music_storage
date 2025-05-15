<?php

namespace App\Controllers;

use App\Models\MediaType;
use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;

class MediaTypes extends \Core\Controller
{
    /**
     * Getting all Media Types
     */
    public function getAction(): void
    {
        $mediaTypes = MediaType::getAll();
        $links = LinkBuilder::mediaTypesLinks(); // Get HATEOAS links
        
        if (!$mediaTypes) {
            ResponseHelper::jsonError('No Media Types found');
            throw new \Exception('No Media Types found', 404);
        }

        ResponseHelper::jsonResponse($mediaTypes, $links);
    }
}