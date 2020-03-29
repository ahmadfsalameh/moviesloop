<?php

    class Google{

        private $client;
        private $clientId;
        private $clientSecret;
        private $applicationName;
        private $redirectUri;
        private $scopes;


        function __construct($client){

            $this->client          = $client;


            $this->clientId        = "client_id";
            $this->clientSecret    = "client_secret";
            $this->applicationName = "app_name";
            $this->redirectUri     = "redirectURI";
            $this->scopes          = "email profile";

        }

        function buildClient(){

            $client = $this->client;

            $client->setClientId($this->clientId);
            $client->setClientSecret($this->clientSecret);
            $client->setApplicationName($this->applicationName);
            $client->setRedirectUri($this->redirectUri);
            $client->addScope($this->scopes);

            return $client;

        }

        function buildAuthUrl(){

            $client = $this->buildClient();

            $authUrl = $client->createAuthUrl();

            return $authUrl;

        }


    }