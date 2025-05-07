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
     * @param string (optional) $self - the current endpoint
     * @return array - links for HATEOAS response
     */
    public static function artistLinks(int $artistID, ?string $self = null): array
    {
        $selfLink = $self ? $self : "/artists/$artistID";

        $links = [
            'self' => ['href' => $selfLink],
            'artist' => ['href' => "/artists/$artistID"],
            'albums' => ['href' => "/artists/$artistID/albums"],
            'delete' => ['href' => "/artists/$artistID", 'method' => 'DELETE']
        ];

        return $links;
    }

    /**
     * links for /artists resources
     * @param string (optional) $self - the current endpoint
     * @return array - links for HATEOAS response
     */
    public static function artistCollectionLinks(?string $self = '/artists'): array
    {
        return [
            'self' => ['href' => $self],
            'all' =>  [ 'href' => '/artists' ],
            'search' => ['href' => '/artists?s={search}'],
            'create' => ['href' => '/artists', 'method' => 'POST'],
        ];
    }

    /**
     * links for /albums/{id} resources
     * @param int $albumID - id from the request
     * @param string (optional) $self - the current endpoint
     * @return array - links for HATEOAS response
     */
    public static function albumLinks(int $albumID, ?string $self = null): array
    {
        $selfLink = $self ? $self : "/albums/$albumID";

        $links = [
            'self' => ['href' => $selfLink],
            'album' => ['href' => "/albums/$albumID"],
            'tracks' => ['href' => "/albums/$albumID/tracks"],
            'update' => ['href' => "/albums/$albumID", 'method' => 'POST'],
            'delete' => ['href' => "/albums/$albumID", 'method' => 'DELETE']
        ];

        return $links;
    }

    /**
     * links for /albums resources
     * @param string (optional) $self - the current endpoint
     * @return array - links for HATEOAS response
     */
    public static function albumCollectionLinks(?string $self = '/albums'): array
    {
        return [
            'self' => ['href' => $self],
            'all' =>  [ 'href' => '/albums'],
            'search' => ['href' => '/albums?s={search}'],
            'create' => ['href' => '/albums', 'method' => 'POST'],
        ];
    }

    /**
     * links for /tracks/{id} resources
     * @param int $trackID - id from the request
     * @param string (optional) $self - the current endpoint
     * @return array - links for HATEOAS response
     */
    public static function trackLinks(int $trackID, ?string $self = null): array
    {
        $selfLink = $self ? $self : "/tracks/$trackID";

        $links = [
            'self' => ['href' => $selfLink],
            'track' => ['href' => "/tracks/$trackID"],
            'update' => ['href' => "/tracks/$trackID", 'method' => 'POST'],
            'delete' => ['href' => "/tracks/$trackID", 'method' => 'DELETE']
        ];

        return $links;
    }

     /**
     * links for /tracks resources
     * @param string (optional) $self - the current endpoint
     * @return array - links for HATEOAS response
     */
    public static function trackCollectionLinks(?string $self = '/tracks?s={search}'): array
    {
        return [
            'self' => ['href' => $self],
            'search' => ['href' => '/tracks?s={search}'],
            'composer' => ['href' => '/tracks?composer={search}'],
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
            'self' => ['href' => '/media-types'],
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
            'self' => ['href' => '/genres'],
        ];

        return $links; 
    }

    /**
     * links for /playlists/{id} resources
     * @param int $playlistID - id from the request
     * @param string (optional) $self - the current endpoint
     * @param int $trackID - track id from the request
     * @return array - links for HATEOAS response
     */
    public static function playlistLinks(int $playlistID, ?string $self = null, int $trackID): array
    {
        $selfLink = $self ? $self : "/playlists/$playlistID";

        $links = [
            'self' => ['href' => $selfLink],
            'playlist' => ['href' => "/playlists/$playlistID"],
            'add track' => ['href' => "/playlists/$playlistID/tracks/$trackID", 'method' => 'POST'],
            'remove track' => ['href' => "/playlists/$playlistID/tracks/$trackID", 'method' => 'DELETE'],
            'delete' => ['href' => "/playlists/$playlistID", 'method' => 'DELETE']
        ];

        return $links;
    }

    /**
     * links for /playlists resources
     * @param string (optional) $self - the current endpoint
     * @return array - links for HATEOAS response
     */
    public static function playlistCollectionLinks(?string $self = '/playlists'): array
    {
        return [
            'self' => ['href' => $self],
            'all' =>  [ 'href' => '/playlists'],
            'search' => ['href' => '/playlists?s={search}'],
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
                'all' =>  [ 'href' => '/artists' ],
                'search' => ['href' => '/artists?s={search}'],
                'create' => ['href' => '/artists', 'method' => 'POST'],
                'artist' => ['href' => "/artists/{artist_id}"],
                'albums' => ['href' => "/artists/{artist_id}/albums"],
                'delete' => ['href' => "/artists/{artist_id}", 'method' => 'DELETE'],
            ],
            'albums' => [
                'all' =>  [ 'href' => '/albums'],
                'search' => ['href' => '/albums?s={search}'],
                'create' => ['href' => '/albums', 'method' => 'POST'],
                'album' => ['href' => "/albums/{album_id}"],
                'tracks' => ['href' => "/albums/{album_id}/tracks"],
                'update' => ['href' => "/albums/{album_id}", 'method' => 'POST'],
                'delete' => ['href' => "/albums/{album_id}", 'method' => 'DELETE']
            ],
            'tracks' => [
                'track' => ['href' => "/tracks/{track_id}"],
                'update' => ['href' => "/tracks/{track_id}", 'method' => 'POST'],
                'delete' => ['href' => "/tracks/{track_id}", 'method' => 'DELETE'],
                'search' => ['href' => '/tracks?s={search}'],
                'composer' => ['href' => '/tracks?composer={search}'],
                'create' => ['href' => '/tracks', 'method' => 'POST'],
            ],
            'media_types' => [
                'all' => '/media-types'
            ],
            'genres' => [
                'all' => '/genres'
            ],
            'playlists' => [
                'playlist' => ['href' => "/playlists/{playlist_id}"],
                'add track' => ['href' => "/playlists/{playlist_id}/tracks/{track_id}", 'method' => 'POST'],
                'remove track' => ['href' => "/playlists/{playlist_id}/tracks/{track_id}", 'method' => 'DELETE'],
                'delete' => ['href' => "/playlists/{playlist_id}", 'method' => 'DELETE'],
                'all' =>  [ 'href' => '/playlists'],
                'search' => ['href' => '/playlists?s={search}'],
                'create' => ['href' => '/playlists', 'method' => 'POST'],
            ]
        ];

        return $links;
    }

}