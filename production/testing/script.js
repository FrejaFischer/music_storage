// const fetchURL = "http://digital-media-api.infinityfreeapp.com/api/"; // Production
const fetchURL = "http://localhost:8888/exam/music_storage/public/"; // Development

// Fetch function
function testApi(e, endpoint, method = "GET", body = null) {
  const parentSection = e.closest("section");
  const output = parentSection.querySelector(".output");

  const options = {
    method: method,
  };

  // Add FormData body for POST
  if (body && method === "POST") {
    const formData = new FormData();
    for (const key in body) {
      if (body.hasOwnProperty(key)) {
        formData.append(key, body[key]);
      }
    }
    options.body = formData;
  }

  const fetchURLWithEndpoint = fetchURL + endpoint;

  fetch(fetchURLWithEndpoint, options)
    .then((response) => response.text())
    .then((data) => {
      if (output) output.textContent = data;
    })
    .catch((error) => {
      if (output) output.textContent = "Error: " + error;
    });
}

// Endpoints
// Artists
document.querySelector(".all_artists").addEventListener("click", (e) => testApi(e.currentTarget, "artists?api_key=abcd1234"));
document.querySelector(".search_artist").addEventListener("click", (e) => testApi(e.currentTarget, "artists?s=hot&api_key=abcd1234"));
document.querySelector(".artist").addEventListener("click", (e) => testApi(e.currentTarget, "artists/30?api_key=abcd1234"));
document.querySelector(".artist_albums").addEventListener("click", (e) => testApi(e.currentTarget, "artists/24/albums?api_key=abcd1234"));
document.querySelector(".artist_delete").addEventListener("click", (e) => testApi(e.currentTarget, "artists/30?api_key=abcd1234", "DELETE"));
document.querySelector(".artist_add").addEventListener("click", (e) =>
  testApi(e.currentTarget, "artists?api_key=abcd1234", "POST", {
    name: "New Artist",
  })
);

// Albums
document.querySelector(".all_albums").addEventListener("click", (e) => testApi(e.currentTarget, "albums?api_key=abcd1234"));
document.querySelector(".search_albums").addEventListener("click", (e) => testApi(e.currentTarget, "albums?s=best&api_key=abcd1234"));
document.querySelector(".album").addEventListener("click", (e) => testApi(e.currentTarget, "albums/10?api_key=abcd1234"));
document.querySelector(".albums_tracks").addEventListener("click", (e) => testApi(e.currentTarget, "albums/24/tracks?api_key=abcd1234"));
document.querySelector(".album_delete").addEventListener("click", (e) => testApi(e.currentTarget, "albums/348?api_key=abcd1234", "DELETE"));
document.querySelector(".album_add").addEventListener("click", (e) =>
  testApi(e.currentTarget, "albums?api_key=abcd1234", "POST", {
    title: "New album",
    artist_id: 2,
  })
);
document.querySelector(".album_update").addEventListener("click", (e) =>
  testApi(e.currentTarget, "albums/10?api_key=abcd1234", "POST", {
    title: "New title",
    artist_id: 2,
  })
);

// Tracks
document.querySelector(".search_tracks_name").addEventListener("click", (e) => testApi(e.currentTarget, "tracks?s=best&api_key=abcd1234"));
document.querySelector(".search_tracks_composer").addEventListener("click", (e) => testApi(e.currentTarget, "tracks?composer=philip&api_key=abcd1234"));
document.querySelector(".track").addEventListener("click", (e) => testApi(e.currentTarget, "tracks/10?api_key=abcd1234"));
document.querySelector(".track_delete").addEventListener("click", (e) => testApi(e.currentTarget, "tracks/3506?api_key=abcd1234", "DELETE"));
document.querySelector(".track_add").addEventListener("click", (e) =>
  testApi(e.currentTarget, "tracks?api_key=abcd1234", "POST", {
    name: "New track",
    album_id: 2,
    media_type_id: 2,
    genre_id: 2,
    composer: "Good composer",
    milliseconds: 1000,
    bytes: 100000,
    unit_price: 9.99,
  })
);
document.querySelector(".track_update").addEventListener("click", (e) =>
  testApi(e.currentTarget, "tracks/10?api_key=abcd1234", "POST", {
    name: "Updated track",
    album_id: 2,
    media_type_id: 2,
    genre_id: 2,
    composer: "Good composer",
    milliseconds: 1000,
    bytes: 100000,
    unit_price: 9.99,
  })
);

// Media Types
document.querySelector(".media_types").addEventListener("click", (e) => testApi(e.currentTarget, "media-types?api_key=abcd1234"));

// Genres
document.querySelector(".genres").addEventListener("click", (e) => testApi(e.currentTarget, "genres?api_key=abcd1234"));

// Clear
document.querySelectorAll(".clear").forEach((el) => {
  el.addEventListener("click", () => {
    const parentSection = el.closest("section");
    const output = parentSection.querySelector(".output");
    if (output) output.innerText = "";
  });
});
