# PHP exam project - API for music storage

This is an PHP REST API for music storage. The API requires an API key to work.

## Endpoints

You can request these endpoints:

### artists

Retrieves all artists:

- GET /artists

Search for artist by name:

- GET /artists?s=search

Get specific artist by id:

- GET /artists/{artist_id}

Retrieves all albums by an artist:

- GET /artists/{artist_id}/albums

Create new artist:

- POST /artists
- Parameters: name

Delete an artist by id:

- DELETE /artists/{artist_id}

### albums

Retrives all albums, including their artist:

- GET /albums

Search for album by title:

- GET /albums?s=search

Get specific album by id:

- GET /albums/{album_id}

Retrives all tracks in an album, including media-type and genre:

- GET /albums/{album_id}/tracks

Create new album:

- POST /albums
- Parameters: title, artist_id

Update an album:

- POST /albums/{album_id}
- Parameters: title?, artist_id?

Delete an album by id:

- DELETE /albums/{album_id}

### tracks

Search for tracks by name:

- GET /tracks?s=search

Get specific track by id:

- GET /tracks/{track_id}

Search for tracks by composer:

- GET /tracks?composer=composer_search

Create a new track:

- POST /tracks
- Parameters: name, album_id, media_type_id, genre_id, composer, milliseconds, bytes, unit_price

Update a track:

- POST /tracks/{track_id}
- Parameters: name?, album_id?, media_type_id?, genre_id?, composer?, milliseconds?, bytes?, unit_price?

Delete a track by id:

- DELETE /tracks/{track_id}

### genres

Retrieves all genres:

- GET /genres

### media-types

Retrieves all media types:

- GET /media-types

### playlists

Retrieves all playlists:

- GET /playlists

Search for playlist by name:

- GET /playlists?s=search

Get specific playlist by id:

- GET /playlists/{playlist_id}

Create new playlist:

- POST /playlists
- Parameters: name

Assign a track to a playlist:

- POST /playlists/{playlist_id}/tracks
- Parameters: track_id

Remove a track from a playlist:

- DELETE /playlists/{playlist_id}/tracks/{track_id}

Delete a playlist by id:

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
