<?php

    class Connection {

        protected function connect() {

            $hostname = "host";
            $username = "user";
            $password = "pass";
            $database = "db";

            try{
                $mysql = new mysqli($hostname, $username, $password, $database);
                return $mysql;
            }catch(Exciption $e){
                die("Couldn't Connect.");
            }

        }

    }