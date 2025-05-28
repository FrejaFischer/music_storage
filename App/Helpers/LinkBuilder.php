<?php

namespace App\Helpers;

/**
 * Class for building HATEOAS for the JSON responses
 */
class LinkBuilder
{

    /**
     * links for /artists/{id} resources
     * @param int $artistID - id from the request
     * @param string $self - the current endpoint
     * @param string $method (optional) - the method of current endpoint
     * @return array - links for HATEOAS response
     */
    public static function artistLinks(int $artistID, string $self, ?string $method = null): array
    {
        $selfMethod = $method ? $method : 'GET';

        $links = [
            'self' => ['href' => $self, 'method' => $selfMethod],
            'artist' => ['href' => "/artists/$artistID", 'method' => 'GET'],
            'albums' => ['href' => "/artists/$artistID/albums", 'method' => 'GET'],
            'delete' => ['href' => "/artists/$artistID", 'method' => 'DELETE']
        ];

        return $links;
    }

    /**
     * links for /artists resources
     * @param string $self - the current endpoint
     * @param string $method (optional) - the current endpoints method
     * @return array - links for HATEOAS response
     */
    public static function artistCollectionLinks(string $self, ?string $method = null): array
    {
        $selfMethod = $method ? $method : 'GET';

        return [
            'self' => ['href' => $self, 'method' => $selfMethod],
            'all' =>  [ 'href' => '/artists', 'method' => 'GET'],
            'search' => ['href' => '/artists?s={search}', 'method' => 'GET'],
            'create' => ['href' => '/artists', 'method' => 'POST'],
        ];
    }

    /**
     * links for /albums/{id} resources
     * @param int $albumID - id from the request
     * @param string $self - the current endpoint
     * @param string $method (optional) - the current endpoints method
     * @return array - links for HATEOAS response
     */
    public static function albumLinks(int $albumID, string $self, ?string $method = null): array
    {
        $selfMethod = $method ? $method : 'GET';

        $links = [
            'self' => ['href' => $self, 'method' => $selfMethod],
            'album' => ['href' => "/albums/$albumID", 'method' => 'GET'],
            'tracks' => ['href' => "/albums/$albumID/tracks", 'method' => 'GET'],
            'update' => ['href' => "/albums/$albumID", 'method' => 'POST'],
            'delete' => ['href' => "/albums/$albumID", 'method' => 'DELETE']
        ];

        return $links;
    }

    /**
     * links for /albums resources
     * @param string $self - the current endpoint
     * @param string $method (optional) - the current endpoints method
     * @return array - links for HATEOAS response
     */
    public static function albumCollectionLinks(string $self, ?string $method = null): array
    {
        $selfMethod = $method ? $method : 'GET';

        return [
            'self' => ['href' => $self, 'method' => $selfMethod],
            'all' =>  [ 'href' => '/albums', 'method' => 'GET'],
            'search' => ['href' => '/albums?s={search}', 'method' => 'GET'],
            'create' => ['href' => '/albums', 'method' => 'POST'],
        ];
    }

    /**
     * links for /tracks/{id} resources
     * @param int $trackID - id from the request
     * @param string $self - the current endpoint
     * @param string $method (optional) - the current endpoints method
     * @return array - links for HATEOAS response
     */
    public static function trackLinks(int $trackID, string $self, ?string $method = null): array
    {
        $selfMethod = $method ? $method : 'GET';

        $links = [
            'self' => ['href' => $self, 'method' => $selfMethod],
            'track' => ['href' => "/tracks/$trackID", 'method' => 'GET'],
            'update' => ['href' => "/tracks/$trackID", 'method' => 'POST'],
            'delete' => ['href' => "/tracks/$trackID", 'method' => 'DELETE']
        ];

        return $links;
    }

     /**
     * links for /tracks resources
     * @param string $self - the current endpoint
     * @param string $method (optional) - the current endpoints method
     * @return array - links for HATEOAS response
     */
    public static function trackCollectionLinks(string $self, ?string $method = null): array
    {
        $selfMethod = $method ? $method : 'GET';

        return [
            'self' => ['href' => $self, 'method' => $selfMethod],
            'search' => ['href' => '/tracks?s={search}', 'method' => 'GET'],
            'composer' => ['href' => '/tracks?composer={search}', 'method' => 'GET'],
            'create' => ['href' => '/tracks', 'method' => 'POST'],
        ];
    }

    /**
     * links for /media-types resources
     * @return array - links for HATEOAS response
     */
    public static function mediaTypesLinks(): array
    {
        $links = [
            'self' => ['href' => '/media-types', 'method' => 'GET'],
        ];

        return $links; 
    }

    /**
     * links for /genres resources
     * @return array - links for HATEOAS response
     */
    public static function genreLinks(): array
    {
        $links = [
            'self' => ['href' => '/genres', 'method' => 'GET'],
        ];

        return $links; 
    }

    /**
     * links for /playlists/{id} resources
     * @param int $playlistID - id from the request
     * @param string $self - the current endpoint
     * @param string $method (optional) - the current endpoints method
     * @param int $trackID - track id from the request
     * @return array - links for HATEOAS response
     */
    public static function playlistLinks(int $playlistID, string $self, ?string $method = null, ?int $trackID = null): array
    {
        $selfMethod = $method ? $method : 'GET';

        $links = [
            'self' => ['href' => $self, 'method' => $selfMethod],
            'playlist' => ['href' => "/playlists/$playlistID", 'method' => 'GET'],
            'add track' => ['href' => "/playlists/$playlistID/tracks/$trackID", 'method' => 'POST'],
            'remove track' => ['href' => "/playlists/$playlistID/tracks/$trackID", 'method' => 'DELETE'],
            'delete' => ['href' => "/playlists/$playlistID", 'method' => 'DELETE']
        ];

        return $links;
    }

    /**
     * links for /playlists resources
     * @param string $self - the current endpoint
     * @param string $method (optional) - the current endpoints method
     * @return array - links for HATEOAS response
     */
    public static function playlistCollectionLinks(string $self, ?string $method = null): array
    {
        $selfMethod = $method ? $method : 'GET';

        return [
            'self' => ['href' => $self, 'method' => $selfMethod],
            'all' =>  [ 'href' => '/playlists', 'method' => 'GET'],
            'search' => ['href' => '/playlists?s={search}', 'method' => 'GET'],
            'create' => ['href' => '/playlists', 'method' => 'POST'],
        ];
    }

    /**
     * links for all endpoints
     * @return array - links for HATEOAS response
     */
    public static function allLinks(): array
    {
        $links = [
            'self' => ['href' => '/'],
            'artists' => [
                'all' =>  [ 'href' => '/artists', 'method' => 'GET'],
                'search' => ['href' => '/artists?s={search}', 'method' => 'GET'],
                'create' => ['href' => '/artists', 'method' => 'POST'],
                'artist' => ['href' => "/artists/{artist_id}", 'method' => 'GET'],
                'albums' => ['href' => "/artists/{artist_id}/albums", 'method' => 'GET'],
                'delete' => ['href' => "/artists/{artist_id}", 'method' => 'DELETE'],
            ],
            'albums' => [
                'all' =>  [ 'href' => '/albums', 'method' => 'GET'],
                'search' => ['href' => '/albums?s={search}', 'method' => 'GET'],
                'create' => ['href' => '/albums', 'method' => 'POST'],
                'album' => ['href' => "/albums/{album_id}", 'method' => 'GET'],
                'tracks' => ['href' => "/albums/{album_id}/tracks", 'method' => 'GET'],
                'update' => ['href' => "/albums/{album_id}", 'method' => 'POST'],
                'delete' => ['href' => "/albums/{album_id}", 'method' => 'DELETE']
            ],
            'tracks' => [
                'track' => ['href' => "/tracks/{track_id}", 'method' => 'GET'],
                'update' => ['href' => "/tracks/{track_id}", 'method' => 'POST'],
                'delete' => ['href' => "/tracks/{track_id}", 'method' => 'DELETE'],
                'search' => ['href' => '/tracks?s={search}', 'method' => 'GET'],
                'composer' => ['href' => '/tracks?composer={search}', 'method' => 'GET'],
                'create' => ['href' => '/tracks', 'method' => 'POST'],
            ],
            'media_types' => [
                'all' => ['href'=>'/media-types', 'method' => 'GET']
            ],
            'genres' => [
                'all' => ['href'=>'/genres', 'method' => 'GET']
            ],
            'playlists' => [
                'playlist' => ['href' => "/playlists/{playlist_id}", 'method' => 'GET'],
                'add track' => ['href' => "/playlists/{playlist_id}/tracks/{track_id}", 'method' => 'POST'],
                'remove track' => ['href' => "/playlists/{playlist_id}/tracks/{track_id}", 'method' => 'DELETE'],
                'delete' => ['href' => "/playlists/{playlist_id}", 'method' => 'DELETE'],
                'all' =>  [ 'href' => '/playlists', 'method' => 'GET'],
                'search' => ['href' => '/playlists?s={search}', 'method' => 'GET'],
                'create' => ['href' => '/playlists', 'method' => 'POST'],
            ]
        ];

        return $links;
    }

}