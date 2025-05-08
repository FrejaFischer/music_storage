// Development
// function testApi(e, endpoint, method = "GET", body = null) {
//   const parentSection = e.closest("section");
//   const output = parentSection.querySelector(".output");

//   const options = {
//     method: method,
//   };

//   // Add FormData body for POST
//   if (body && method === "POST") {
//     const formData = new FormData();
//     formData.append("name", "New Artist");
//     options.body = formData;
//   }

//   fetch(`http://localhost:8888/exam/music_storage/public/${endpoint}`, options)
//     .then((response) => response.text())
//     .then((data) => {
//       if (output) output.textContent = data;
//     })
//     .catch((error) => {
//       if (output) output.textContent = "Error: " + error;
//     });
// }

// Production
function testApi(e, endpoint, method = "GET", body = null) {
  const parentSection = e.closest("section");
  const output = parentSection.querySelector(".output");

  const options = {
    method: method,
  };

  // Add FormData body for POST
  if (body && method === "POST") {
    const formData = new FormData();
    formData.append("name", "New Artist");
    options.body = formData;
  }

  fetch(`https://digital-media-api.infinityfreeapp.com/api/${endpoint}`, options)
    .then((response) => response.text())
    .then((data) => {
      if (output) output.textContent = data;
    })
    .catch((error) => {
      if (output) output.textContent = "Error: " + error;
    });
}

// Endpoints
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

// Clear
document.querySelectorAll(".clear").forEach((el) => {
  el.addEventListener("click", () => {
    const parentSection = el.closest("section");
    const output = parentSection.querySelector(".output");
    if (output) output.innerText = "";
  });
});
