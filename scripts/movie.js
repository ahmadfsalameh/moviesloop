checkDOMReady(event => domReady(event));

const domReady = event => {
  getData("../api.php?home-movies", handleMoviesResponse);
};

const handleMoviesResponse = data => {
  const error = data.error;

  if (error == true) {
    return;
  }

  const movies = data.movies;

  if (movies.length < 1) {
    return;
  }

  const urlArray = window.location.href.split("/");

  for (let x = 0; x < movies.length; x++) {
    const title = movies[x].title;

    const movie = urlArray[urlArray.length - 1].split("-").join(" ");

    if (movie == title) {
      continue;
    }

    const poster = movies[x].poster;

    const moviesContainer = _("article div.movies-container");
    moviesContainer.innerHTML += `
    <div class="movie">
        <a href="./${title.split(" ").join("-")}"></a>
        <div class="image-container">
            <img src="../posters/${poster}" />
        </div>
        <h1>${title}</h1>
    </div>
    `;
  }
};
