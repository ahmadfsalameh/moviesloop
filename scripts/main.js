function checkDOMReady(callback) {
  if (document.readyState != "loading") callback(event);
  else document.addEventListener("DOMContentLoaded", callback);
}
checkDOMReady(event => {
  start();
});
const start = () => {
  const googleBtns = _(["button.google-signin-btn"]);
  for (let x = 0; x < googleBtns.length; x++) {
    googleBtns[x].addEventListener("click", () => redirectGoogleSignIn());
  }

  getData("https://moviesloop.herokuapp.com/api.php?user", handleUser);
  getData("https://moviesloop.herokuapp.com/api.php?history", handleHistory);
};
const _ = element => {
  //check for array
  if (Array.isArray(element) == true)
    return document.querySelectorAll(element[0]);
  else {
    return document.querySelector(element);
  }
};
const getData = function(url, callback) {
  fetch(url)
    .then(res => res.json())
    .then(data => callback(data))
    .catch(err => console.log(err));
};
const toggleNav = () => {
  let main = _("main");
  let aside = _("aside");
  let windowWidth = window.innerWidth;
  if (main.classList.contains("isMobile")) {
    main.classList.remove("isMobile");
    if (windowWidth <= 1000) {
      return;
    }
  }
  if (main.classList.length == 1) {
    main.classList.remove("nav-opened");
  } else {
    main.classList.add("nav-opened");
  }
};
const clearSearch = () => {
  let searchform = _(".searchform");
  _("form.searchform input").value = "";
  if (searchform.classList.contains("expanded")) {
    searchform.classList.remove("expanded");
  }
};
const toggleSearch = () => {
  let searchform = _(".searchform");
  searchform.classList.add("expanded");
};
const redirectGoogleSignIn = () => {
  window.localStorage.setItem("moviesloop-redirect", window.location.href);
  window.location.href =
    "https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=online&client_id=652111404930-0ktjssu5i46fcnkpjji3h7u4b7kkvaf5.apps.googleusercontent.com&redirect_uri=https%3A%2F%2Fmoviesloop.herokuapp.com%2Fapi.php&state&scope=email%20profile&approval_prompt=auto";
};
const handleUser = data => {
  let error = data.error;

  if (error) {
    return;
  }

  const userInfoContainer = _("div.user-opt-container");
  userInfoContainer.innerHTML = "";

  let p = document.createElement("p");
  p.innerText = data.name;

  let img = document.createElement("img");
  img.src = data.picture;

  userInfoContainer.append(img);
  userInfoContainer.append(p);
};

const handleHistory = data => {
  let error = data.error;

  if (error) {
    return;
  }

  let historyContainer = _("div.history");

  historyContainer.innerHTML = "";

  let history = data.history;

  if (history.length == 0) {
    historyContainer.innerHTML = "<p>Your watch history is empty.</p>";
    return;
  }

  historyContainer.innerHTML = `
    <p>Watch history</p>
    <div class="movies-container"></di>
  `;

  let historyMoviesContainer = _("div.history div.movies-container");

  for (let x = 0; x < history.length; x++) {
    let title = history[x].title;
    let poster = history[x].poster;

    let url = title.split(" ").join("-");

    historyMoviesContainer.innerHTML += `
    
      <div id="movie">
      
        <a href="https://moviesloop.herokuapp.com/movie/${url}"></a>
        <div class="image-container">
          <img src="https://moviesloop.herokuapp.com/posters/${poster}" alt="${title}" />
        </div>
        <p>${title}</p>
      
      </div>
    
    `;
  }
};
