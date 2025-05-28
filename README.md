# PHP exam project - API for music storage

This is an PHP REST API for music storage. The API requires an API key to work.

## Endpoints

You can request these endpoints:

### artists

- GET /artists
- GET /artists?s=search
- GET /artists/{artist_id}
- GET /artists/{artist_id}/albums
- POST /artists
- DELETE /artists/{artist_id}

### albums

- GET /albums
- GET /albums?s=search
- GET /albums/{album_id}
- GET /albums/{album_id}/tracks
- POST /albums
- POST /albums/{album_id}
- DELETE /albums/{album_id}

### tracks

- GET /tracks?s=search
- GET /tracks/{track_id}
- GET /tracks?composer=composer_search
- POST /tracks
- POST /tracks/{track_id}
- DELETE /tracks/{track_id}

### genres

- GET /genres

### media-types

- GET /media-types

### playlists

- GET /playlists
- GET /playlists?s=search
- GET /playlists/{playlist_id}
- POST /playlists
- POST /playlists/{playlist_id}/tracks
- DELETE /playlists/{playlist_id}/tracks/{track_id}
- DELETE /playlists/{playlist_id}

## Security measurements

When using this API please prevent XSS attacks by escaping / encoding all the data received from the API.

## Data

Data from Chinook database:
[Link to Chinook Github](https://github.com/lerocha/chinook-database)

## Author

Freja Fischer Nielsen

## Tech

Made in the MVC pattern in PHP version 8.3.14

_PHP Elective - Web Development, KEA 2025_
