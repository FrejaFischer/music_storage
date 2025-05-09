// Development
function testApi(e, endpoint, method = "GET", body = null, from = null) {
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

  fetch(`http://localhost:8888/exam/music_storage/public/${endpoint}`, options)
    .then((response) => response.text())
    .then((data) => {
      if (output) output.textContent = data;
    })
    .catch((error) => {
      if (output) output.textContent = "Error: " + error;
    });
}

// Production
// Fetch function with POST
// function testApi(e, endpoint, method = "GET", body = null) {
//   const parentSection = e.closest("section");
//   const output = parentSection.querySelector(".output");

//   const options = {
//     method: method,
//   };

//   // Add FormData body for POST
//   if (body && method === "POST") {
//     const formData = new FormData();
//     for (const key in body) {
//       if (body.hasOwnProperty(key)) {
//         formData.append(key, body[key]);
//       }
//     }
//     options.body = formData;
//   }

//   fetch(`http://digital-media-api.infinityfreeapp.com/api/${endpoint}`, options)
//     .then((response) => response.text())
//     .then((data) => {
//       if (output) output.textContent = data;
//     })
//     .catch((error) => {
//       if (output) output.textContent = "Error: " + error;
//     });
// }

// Simple solution
// function testApi(e, endpoint, method = "GET") {
//   fetch(`http://digital-media-api.infinityfreeapp.com/api/${endpoint}`, {
//     method: method,
//   })
//     .then((response) => response.text())
//     .then((data) => {
//       const parentSection = e.closest("section");
//       const output = parentSection.querySelector(".output");
//       if (output) output.textContent = data;
//     })
//     .catch((error) => {
//       const parentSection = e.closest("section");
//       const output = parentSection.querySelector(".output");
//       if (output) output.textContent = error;
//     });
// }

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

// Clear
document.querySelectorAll(".clear").forEach((el) => {
  el.addEventListener("click", () => {
    const parentSection = el.closest("section");
    const output = parentSection.querySelector(".output");
    if (output) output.innerText = "";
  });
});
