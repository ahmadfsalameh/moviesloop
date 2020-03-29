<?php

    class Movie {

      static public function getMovie($movie) {

        $url = "https://moviesloop.herokuapp.com/api.php?movie=$movie";

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        $res = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($res, TRUE);

      }

      static public function checkUser() {

        if(!isset($_COOKIE['authToken'])) {

          return ['error' => 1];

        }

        $url = "https://moviesloop.herokuapp.com/api.php";

        $post = [
          'authToken' => $_COOKIE['authToken']
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        $res = curl_exec($curl);
        curl_close($curl);

        $res = json_decode($res, TRUE);

        $error = $res['error'];

        if($error) {
          return ['error' => 1];
        }

        return $res;

      }

      static public function addHistory($user_id, $movie_id) {
        
        $url = "https://moviesloop.herokuapp.com/api.php";

        $post = [
          'user_id' => $user_id,
          'movie_id' => $movie_id
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        curl_exec($curl);
        curl_close($curl);

      }

    }

    if(!isset($_GET['q'])){
      die();
    }

    $movie = $_GET['q'];

    $res = Movie::getMovie($movie);

    $error = $res['error'];

    if($error == 1){
      die();
    }

    $user = Movie::checkUser();

    $userError = $user['error'];

    $movie_id = $res['movie']['id'];
    $title = $res['movie']['title'];
    $poster = $res['movie']['poster'];
    $story = $res['movie']['story'];
    $embed = $res['movie']['embed'];
    $date = $res['movie']['date'];

    if(!$userError) {
      $user_id = $user['id'];
      Movie::addHistory($user_id, $movie_id);
    }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../styles/home.css" />
    <link rel="stylesheet" href="../styles/movie.css" />
    <link rel="shortcut icon" href="https://moviesloop.herokuapp.com/vectors/ico.svg" type="image/svg">
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap"
      rel="stylesheet"
    />
    <script src="../scripts/main.js"></script>
    <script src="../scripts/movie.js"></script>
  </head>
  <body>
    <header>
      <section class="left-section">
        <button
          title="nav-toggler"
          class="button nav-toggler"
          onclick="toggleNav()"
        ></button>
        <a class="logo_link" href="https://moviesloop.herokuapp.com/moviesloop">
          <img
            class="logo"
            src="../vectors/moviesloop-logo.svg"
            alt="Moviesloop Logo"
          />
        </a>
        <form
          name="searchform"
          class="searchform"
          action="../explore.php"
          method="GET"
        >
          <input
            type="text"
            name="q"
            placeholder="Looking for?"
            autocomplete="off"
            maxlength="80"
          />
          <input type="submit" hidden />
          <button
            class="button"
            onclick="event.preventDefault();clearSearch()"
          ></button>
        </form>
      </section>
      <section class="right-section">
        <button
          title="search-toggler"
          class="button search-toggler"
          onclick="toggleSearch()"
        ></button>
        <div class="user-opt-container">
          <ul class="horizontal-list signing-opt-list">
            <li class="buttonlink">
              <button class="google-signin-btn">
                <span class="google-signin-svg">
                  <svg
                    width="18"
                    height="19"
                    viewBox="0 0 18 19"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M9 7.84363V11.307H13.8438C13.6365 12.428 12.9994 13.373 12.0489 14.0064V16.2534H14.9562C16.6601 14.6951 17.641 12.4029 17.641 9.67839C17.641 9.04502 17.5854 8.43176 17.4792 7.84865H9V7.84363Z"
                      fill="#3E82F1"
                    ></path>
                    <path
                      d="M9.00001 14.861C6.65394 14.861 4.67192 13.2876 3.96406 11.1714H0.955627V13.4937C2.43709 16.4142 5.48091 18.4198 9.00001 18.4198C11.432 18.4198 13.4697 17.6206 14.9562 16.2533L12.0489 14.0064C11.245 14.5443 10.2135 14.861 9.00001 14.861Z"
                      fill="#32A753"
                    ></path>
                    <path
                      d="M3.96404 5.45605H0.955617C0.348876 6.66246 0 8.02972 0 9.47238C0 10.915 0.348876 12.2823 0.955617 13.4887L3.96404 11.1714C3.78202 10.6335 3.6809 10.0605 3.6809 9.47238C3.6809 8.88426 3.78202 8.31122 3.96404 7.77336V5.45605Z"
                      fill="#F9BB00"
                    ></path>
                    <path
                      d="M0.955627 5.45597L3.96406 7.77327C4.67192 5.65703 6.65394 4.08368 9.00001 4.08368C10.3197 4.08368 11.5079 4.53608 12.4382 5.42078L15.0219 2.85214C13.4646 1.40948 11.427 0.52478 9.00001 0.52478C5.48091 0.52478 2.43709 2.53043 0.955627 5.45597Z"
                      fill="#E74133"
                    ></path>
                  </svg>
                </span>
                <p class="google-signin-text">
                  Sign In with Google
                </p>
              </button>
            </li>
          </ul>
        </div>
      </section>
    </header>
    <main class="nav-opened isMobile">
      <aside>
        <div class="aside-tap explore">
          <ul class="vertical-list">
            <li>
              <a href="../">
                <img src="../vectors/home.svg" alt="home icon" />
                <span>Home</span>
              </a>
            </li>
          </ul>
        </div>
        <div class="aside-tap history">
          <p>Sign In to see your watch history.</p>
          <button class="google-signin-btn">
            <span class="google-signin-svg">
              <svg
                width="18"
                height="19"
                viewBox="0 0 18 19"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M9 7.84363V11.307H13.8438C13.6365 12.428 12.9994 13.373 12.0489 14.0064V16.2534H14.9562C16.6601 14.6951 17.641 12.4029 17.641 9.67839C17.641 9.04502 17.5854 8.43176 17.4792 7.84865H9V7.84363Z"
                  fill="#3E82F1"
                ></path>
                <path
                  d="M9.00001 14.861C6.65394 14.861 4.67192 13.2876 3.96406 11.1714H0.955627V13.4937C2.43709 16.4142 5.48091 18.4198 9.00001 18.4198C11.432 18.4198 13.4697 17.6206 14.9562 16.2533L12.0489 14.0064C11.245 14.5443 10.2135 14.861 9.00001 14.861Z"
                  fill="#32A753"
                ></path>
                <path
                  d="M3.96404 5.45605H0.955617C0.348876 6.66246 0 8.02972 0 9.47238C0 10.915 0.348876 12.2823 0.955617 13.4887L3.96404 11.1714C3.78202 10.6335 3.6809 10.0605 3.6809 9.47238C3.6809 8.88426 3.78202 8.31122 3.96404 7.77336V5.45605Z"
                  fill="#F9BB00"
                ></path>
                <path
                  d="M0.955627 5.45597L3.96406 7.77327C4.67192 5.65703 6.65394 4.08368 9.00001 4.08368C10.3197 4.08368 11.5079 4.53608 12.4382 5.42078L15.0219 2.85214C13.4646 1.40948 11.427 0.52478 9.00001 0.52478C5.48091 0.52478 2.43709 2.53043 0.955627 5.45597Z"
                  fill="#E74133"
                ></path>
              </svg>
            </span>
            <p class="google-signin-text">
              Sign In with Google
            </p>
          </button>
        </div>
        <div class="aside-tap rights">
          <p>
            &copy; <a href="">ahmadsalameh.com</a> 2019. All rights reserved.
          </p>
        </div>
      </aside>
      <article>
        <section class="movie-information">
          <div class="about-movie">
            <div class="left">
              <h1><?php echo $title; ?></h1>
              <p>Story</p>
              <p id="story"><?php echo $story; ?></p>
              <p>Uploaded in: <?php echo $date; ?></p>
            </div>
            <div class="right">
              <img id="poster" src="https://moviesloop.herokuapp.com/posters/<?php echo $poster; ?>" alt="<?php echo $title; ?>">
            </div>
          </div>
          <div class="video-container">
            <?php
            
              if(!$userError) {
                echo $embed;
              }else{
                echo '
                
                <div class="embed-signin-required">
                  <h3>Sign In to watch the movie</h3>
                </div>
                
                ';
              }
            
            ?>
          </div>
        </section>
        <section class="movies-section">
          <h1>Suggesions</h1>
          <div class="movies-container primary-movies-container"></div>
        </section>
      </article>
    </main>
  </body>
</html>
