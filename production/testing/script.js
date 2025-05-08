// Development
// function testApi(e, endpoint, method = "GET") {
//   fetch(`http://localhost:8888/exam/music_storage/public/${endpoint}`, {
//     method: method,
//   })
//     .then((response) => response.text())
// .then((data) => {
//   const parentSection = e.closest("section");
//   const output = parentSection.querySelector(".output");
//   if (output) output.textContent = data;
// })
// .catch((error) => {
//   const parentSection = e.closest("section");
//   const output = parentSection.querySelector(".output");
//   if (output) output.textContent = error;
// });
// }

// Production
function testApi(e, endpoint, method = "GET") {
  fetch(`http://digital-media-api.infinityfreeapp.com/api/${endpoint}`, {
    method: method,
  })
    .then((response) => response.text())
    .then((data) => {
      const parentSection = e.closest("section");
      const output = parentSection.querySelector(".output");
      if (output) output.textContent = data;
    })
    .catch((error) => {
      const parentSection = e.closest("section");
      const output = parentSection.querySelector(".output");
      if (output) output.textContent = error;
    });
}

// Endpoints
document.querySelector(".all_artists").addEventListener("click", (e) => testApi(e.currentTarget, "artists?api_key=abcd1234"));
document.querySelector(".search_artist").addEventListener("click", (e) => testApi(e.currentTarget, "artists?s=hot&api_key=abcd1234"));
document.querySelector(".artist").addEventListener("click", (e) => testApi(e.currentTarget, "artists/30?api_key=abcd1234"));
document.querySelector(".artist_albums").addEventListener("click", (e) => testApi(e.currentTarget, "artists/24/albums?api_key=abcd1234"));
document.querySelector(".artist_delete").addEventListener("click", (e) => testApi(e.currentTarget, "artists/30?api_key=abcd1234", "DELETE"));

// Clear
document.querySelectorAll(".clear").forEach((el) => {
  el.addEventListener("click", () => {
    const parentSection = el.closest("section");
    const output = parentSection.querySelector(".output");
    if (output) output.innerText = "";
  });
});
