<?php

namespace App\Controllers;

use App\Models\Artist;
use App\Helpers\ResponseHelper;
use App\Helpers\LinkBuilder;

class Artists extends \Core\Controller
{
    /**
     * Getting all artists / All artists with name search
     */
    public function getAction(): void
    {
        $search = $_GET['s'] ?? null; // Search for artists by name

        if($search) {
            $artists = Artist::search($search);
            $links = LinkBuilder::artistCollectionLinks('/artists?s={search}'); // Get HATEOAS links
        } else {
            $artists = Artist::getAll();
            $links = LinkBuilder::artistCollectionLinks(); // Get HATEOAS links
        }
        
        if (!$artists) {
            ResponseHelper::jsonError('No artists found');
            throw new \Exception('No artists found', 404);
        }

        ResponseHelper::jsonResponse($artists, $links);
    }

    public function findAction(): void
    {
        $artistID = $this->validateID($this->routeParams['artist_id'] ?? null, 'Artist ID');

        $artist = Artist::get($artistID);

        if (!$artist) {
            ResponseHelper::jsonError('No artist found with that ID');
            throw new \Exception('No artist found with that ID', 404);
        }

        $links = LinkBuilder::artistLinks($artistID); // Get HATEOAS links

        ResponseHelper::jsonResponse($artist, $links);
    }

    public function albumAction(): void
    {
        $artistID = $this->validateID($this->routeParams['artist_id'] ?? null, 'Artist ID');

        // Check if artist exist
        $artist = Artist::get($artistID);

        if (!$artist) {
            ResponseHelper::jsonError('No artist found with that ID');
            throw new \Exception('No artist found with that ID', 404);
        }

        // Get artists albums
        $artistsAlbums = Artist::getAlbums($artistID);

        if (!$artistsAlbums) {
                ResponseHelper::jsonError('No albums found for that artist ID');
                throw new \Exception('No artist found with that ID', 404);
        }

        $links = LinkBuilder::artistLinks($artistID, "/artists/$artistID/albums"); // Get HATEOAS links

        ResponseHelper::jsonResponse($artistsAlbums, $links);

    }

    public function deleteAction():void
    {
        $artistID = $this->validateID($this->routeParams['artist_id'] ?? null, 'Artist ID');

        // Check if artist has any albums
        $artistsAlbums = Artist::getAlbums($artistID);

        if ($artistsAlbums) {
            ResponseHelper::jsonError('Artist still has albums. Artist cannot be deleted');
            throw new \Exception('Artist still has albums. Artist cannot be deleted', 409);
            // 409: request cannot be completed due to a conflict with the current state of the resource.
        }

        $artistIsDeleted = Artist::delete($artistID);

        // If no rows were affected
        if (!$artistIsDeleted) {
            ResponseHelper::jsonError('Artist not found. Nothing was deleted.');
            throw new \Exception('Artist not found. Nothing was deleted.', 404);
        }

        $links = LinkBuilder::artistLinks($artistID, "/artists/$artistID", 'DELETE'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Artist deleted', 'Artist ID' => $artistID], $links);

    }

    public function createAction(): void
    {
        $result = Artist::add($_POST);

        if (gettype($result) === 'array') {
            ResponseHelper::jsonError('Artist not added. Validation errors: ' . $result[0]);
            throw new \Exception('Artist not added. Validation errors: ' . $result[0], 400);
        }

        $links = LinkBuilder::artistLinks($result, "/artists/$result", 'POST'); // Get HATEOAS links

        ResponseHelper::jsonResponse(['Message' => 'Artist succesfully added', 'Artist ID' => $result], $links);
    }

}