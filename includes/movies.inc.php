<?php

    class Movies extends Connection {

        public function getMovies() {

            $connect = $this->connect();

            $query = "SELECT * FROM movies ORDER BY (id) ASC";
            $mysql = mysqli_query($connect, $query);

            if(mysqli_num_rows($mysql) < 1){
                return json_encode(['error' => 1]);
            }

            $row = [];
            $rows = [];

            while($fetch = mysqli_fetch_assoc($mysql)){
                $row['title'] = $fetch['title'];
                $row['poster'] = $fetch['poster'];

                $rows[] = $row;
                $row = [];
            }

            return json_encode(['error' => 0, 'movies' => $rows]);

        }

        public function movie($movie) {

            $connect = $this->connect();

            $query = "SELECT * FROM movies WHERE title = ?";

            $movie = str_replace("-", " ", $movie);

            $stmt = $connect->prepare($query);
            $stmt->bind_param('s', $movie);
            $stmt->execute();

            $result = $stmt->get_result();

            if(mysqli_num_rows($result) < 1){
                return json_encode(['error' => 1]);
            }

            $row = [];

            $fetch = $result->fetch_assoc();
            
            $row['id'] = $fetch['id'];
            $row['title'] = $fetch['title'];
            $row['poster'] = $fetch['poster'];
            $row['story'] = $fetch['story'];
            $row['embed'] = $fetch['embed'];
            $row['date'] = $fetch['uploadTime'];

            return json_encode(['error' => 0, 'movie' => $row]);

        }

        public function search($searchQuery) {

            $connect = $this->connect();

            $query = "SELECT * FROM movies WHERE title LIKE ?";
            
            $stmt = $connect->prepare($query);

            $searchQuery = "%".$searchQuery."%";

            $stmt->bind_param('s', $searchQuery);
            $stmt->execute();

            $result = $stmt->get_result();

            if(mysqli_num_rows($result) < 1){
                return json_encode(['error' => 1]);
            }

            $row = [];
            $rows = [];

            while($fetch = $result->fetch_assoc()){
                $row['title'] = $fetch['title'];
                $row['poster'] = $fetch['poster'];

                $rows[] = $row;
                $row = [];
            }

            return json_encode(['error' => 0, 'movies' => $rows]);

        }

    }