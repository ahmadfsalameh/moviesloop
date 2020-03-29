<?php

    class User extends Connection {

        private function createCookies($authToken) {
            setcookie('authToken', $authToken, time() + 3600 * 24 * 30, "/");
        }

        public function getUser($authToken) {

            $connect = $this->connect();

            $query = "SELECT id, authToken, name, picture FROM users WHERE authToken = ?;";

            $stmt = $connect->prepare($query);
            $stmt->bind_param("s", $authToken);
            $stmt->execute();

            $result = $stmt->get_result();

            if(mysqli_num_rows($result) < 1) {
                return json_encode(['error' => 1]);
            }

            $fetch = $result->fetch_assoc();

            $id = $fetch['id'];
            $name = $fetch['name'];
            $picture = $fetch['picture'];

            return json_encode(['error' => 0, 'id' => $id, 'name' => $name, 'picture' => $picture]);

        }

        public function history($authToken) {

            $user = $this->getUser($authToken);

            $user = json_decode($user, TRUE);

            if($user['error']) {
                return json_encode(['error' => $user]);
            }

            $user_id = $user['id'];

            $connect = $this->connect();

            $query = "SELECT * FROM history WHERE user_id = $user_id ORDER BY id DESC;";
            $mysql = mysqli_query($connect, $query);

            $history = [];
            $row = [];

            if(mysqli_num_rows($mysql) > 0) {

                while($fetch = mysqli_fetch_assoc($mysql)) {

                    $movie_id = $fetch['movie_id'];

                    $queryMovies = "SELECT * FROM movies WHERE id = $movie_id;";
                    $mysqlMovies = mysqli_query($connect, $queryMovies);

                    $fetchMovies = mysqli_fetch_assoc($mysqlMovies);

                    $row['title'] = $fetchMovies['title'];
                    $row['poster'] = $fetchMovies['poster'];

                    $history[] = $row;
                    $row = [];

                }

            }

            return json_encode(['error' => 0, 'history' => $history]);

        }

        private function check($email) {
            $connect = $this->connect();

            $query = "SELECT email FROM users WHERE email = ?;";

            $stmt = $connect->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if(mysqli_num_rows($result) > 0) {
                return true;
            }else{
                return false;
            }
        }

        public function signIn($email, $name, $picture) {

            if($this->check($email)) {

                return $this->getUserData($email);

            }

            return $this->addUser($email, $name, $picture);

        }

        private function getUserData($email) {
            $connect = $this->connect();

            $query = "SELECT email, authToken FROM users WHERE email = ?;";

            $stmt = $connect->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            $fetch = $result->fetch_assoc();

            $authToken = $fetch['authToken'];

            $this->createCookies($authToken);

            return json_encode(['error' => 0]);

        }

        private function addUser($email, $name, $picture) {
            $connect = $this->connect();

            $query = "INSERT INTO users (email, name, authToken, picture) VALUES (?, ?, ?, ?);";

            $authToken = bin2hex(random_bytes(64));

            $stmt = $connect->prepare($query);
            $stmt->bind_param("ssss", $email, $name, $authToken, $picture);
            $stmt->execute();

            $this->createCookies($authToken);

            return json_encode(['error' => 0]);

        }

        public function addHistory($user_id, $movie_id) {

            $connect = $this->connect();

            $queryCheck = "SELECT * FROM history WHERE user_id = $user_id AND movie_id = $movie_id;";
            $mysql = mysqli_query($connect, $queryCheck);

            if(mysqli_num_rows($mysql) > 0) {
                mysqli_query($connect, "DELETE FROM history WHERE user_id = $user_id AND movie_id = $movie_id;");
            }

            $query = "INSERT INTO history (user_id, movie_id) VALUES ($user_id, $movie_id);";
            mysqli_query($connect, $query);

        }

    }