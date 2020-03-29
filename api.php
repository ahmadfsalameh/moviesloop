<?php

    require_once 'vendor/autoload.php';
    require_once 'includes/autoload.php';

    if(isset($_GET['user'])) {

        if(!isset($_COOKIE['authToken'])) {
            echo json_encode(['error' => 1]);
            die();
        }

        $authToken = $_COOKIE['authToken'];

        $User = new User();
        $response = $User->getUser($authToken);

        echo $response;
        die();

    }

    if(isset($_GET['history'])) {

        if(!isset($_COOKIE['authToken'])) {
            echo json_encode(['error' => 1]);
            die();
        }

        $authToken = $_COOKIE['authToken'];

        $User = new User();
        $response = $User->history($authToken);

        echo $response;
        die();

    }

    if(isset($_GET['home-movies'])){

        $Movies = new Movies();
        $response = $Movies->getMovies();

        echo $response;
        die();

    }

    if(isset($_GET['movie'])){

        $movie = $_GET['movie'];

        $Movies = new Movies();
        $response = $Movies->movie($movie);

        echo $response;
        die();

    }

    if(isset($_POST['authToken'])) {

        $authToken = $_POST['authToken'];

        $User = new User();
        $response = $User->getUser($authToken);

        echo $response;
        die();

    }

    if(isset($_POST['user_id']) && isset($_POST['movie_id'])) {

        $user_id = $_POST['user_id'];
        $movie_id = $_POST['movie_id'];

        $User = new User();
        $User->addHistory($user_id, $movie_id);

        die();

    }

    if(isset($_GET['generateGoogleLink'])){

        $newClient = new Google_Client();

        $Google = new Google($newClient);

        $client = $Google->buildClient();

        $authUrl = $Google->buildAuthUrl();

        echo $authUrl;
        die();

    }

    if(isset($_GET['code'])){

        $newClient = new Google_Client();
        $Google = new Google($newClient);
        $client = $Google->buildClient();

        $code = $_GET['code'];

        $access_token = $client->fetchAccessTokenWithAuthCode($code);

        if(isset($access_token['error'])){
            echo "error";
            die();
        }

        $oAuth = new Google_Service_Oauth2($client);

        $googleData = $oAuth->userinfo_v2_me->get();

        $name = $googleData['givenName'] ." ". $googleData['familyName'];
        $email  = $googleData['email'];
        $picture  = $googleData['picture'];

        $User = new User();
        $User->signIn($email, $name, $picture);

        header("location: redirect.html");
    }

    if(isset($_GET['search'])){

        $query = $_GET['search'];

        $Movies = new Movies();
        $response = $Movies->search($query);

        echo $response;
        die();

    }

    echo json_encode(['error' => 1]);