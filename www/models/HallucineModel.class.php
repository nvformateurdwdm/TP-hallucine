<?php

require_once "Model.class.php";
require_once "User.class.php";
require_once "Movie.class.php";
require_once "Genre.class.php";
require_once "MovieUserRating.class.php";

class HallucineModel extends Model{
    private $_movies;
    private $_movie;
    private int $_loginStatus;

    const SORT_MOVIES_BY_TITLE = 0;
    const SORT_MOVIES_BY_RELEASE_DATE = 1;
    const SORT_MOVIES_BY_ADDED_DATE = 2;
    const SORT_MOVIES_BY_RATING = 3;

    const LOGIN_USER_NOT_FOUND = 0;
    const LOGIN_INCORRECT_PASSWORD = 1;
    const LOGIN_OK = 2;

    const MOVIE_USER_RATE = 0;
    const MOVIE_USER_UPDATE_RATE = 1;
    const MOVIE_USER_DELETE_RATE = 2;

    public function requestLogin(string $email, string $password){
        $email = $this->_checkInput($email);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $rows = $this->_getRows(HOST, DB_NAME, LOGIN, PASSWORD, $sql);
        if (count($rows) == 0) {
            $this->_loginStatus = self::LOGIN_USER_NOT_FOUND;
            return;
        } else {
            $value = $rows[0];
            $bool = password_verify($password, $value["password"]);
            if (!$bool) {
                $this->_loginStatus = self::LOGIN_INCORRECT_PASSWORD;
                return;
            } else {
                $user = new User($value["id"], $value["firstname"], $value["lastname"], $value["email"], $value["password"], $value["sex"]);
                $this->_loginStatus = self::LOGIN_OK;
                return $user;
            }
        }
    }

    private function _checkInput($input){
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        if(IS_DEBUG){
            // echo $input;
            // echo "<br>";
        }
        return $input;
    }

    public function requestMovies($sort = self::SORT_MOVIES_BY_TITLE){
        $_movies = array();

        switch ($sort) {
            case self::SORT_MOVIES_BY_TITLE:
                $sql = "SELECT * FROM `movies` ORDER BY title;";
                break;
            case self::SORT_MOVIES_BY_RELEASE_DATE:
                $sql = "SELECT * FROM `movies` ORDER BY release_date DESC;";
                break;
            case self::SORT_MOVIES_BY_ADDED_DATE:
                $sql = "SELECT * FROM `movies` ORDER BY added_date DESC;";
                break;
            case self::SORT_MOVIES_BY_RATING:
                $sql = "SELECT movies.*, AVG(movies_users_ratings.rate) as average_rate
                FROM movies_users_ratings
                    RIGHT JOIN movies
                    ON movies_users_ratings.movie_id = movies.id
                GROUP BY movies.id
                ORDER BY average_rate DESC, movies.title;";
                break;
            default:
                $sql = "SELECT * FROM `movies`;";
                break;
        }

        $rows = $this->_getRows(HOST, DB_NAME, LOGIN, PASSWORD, $sql);

        foreach ($rows as $key => $value) {
            $movie = new Movie($value["id"], $value["title"], $value["image_url"], $value["runtime"], $value["description"], $value["release_date"], $value["added_date"]);
            $this->_movies[] = $movie;
        }
    }

    public function requestMovie(int $movieId){
        $sql = "SELECT movies.*, AVG(movies_users_ratings.rate) as average_rate
                    FROM movies_users_ratings
                    INNER JOIN movies
                    ON movies_users_ratings.movie_id = movies.id
                    WHERE movies.id = $movieId;";
        $rows = $this->_getRows(HOST, DB_NAME, LOGIN, PASSWORD, $sql);
        $value = $rows[0];
        $movie = new Movie($value["id"], $value["title"], $value["image_url"], $value["runtime"], $value["description"], $value["release_date"], $value["added_date"]);

        if($value['average_rate'] != ""){
            $movie->setRate($value['average_rate']);
        }

        $sql = "SELECT movies.id, movies.title, movies_genres.genre_id as movie_genre_id, genres.name FROM movies
                    INNER JOIN movies_genres ON movies_genres.movie_id = movies.id
                    INNER JOIN genres ON genres.id = movies_genres.genre_id
                WHERE movies.id = $movieId;";
        $rows = $this->_getRows(HOST, DB_NAME, LOGIN, PASSWORD, $sql);
        $genres = array();
        foreach ($rows as $key => $value) {
            $genre = new Genre($value["movie_genre_id"], $value["name"]);
            $genres[] = $genre;
        }
        $movie->setGenres($genres);

        $this->_movie = $movie;
    }

    public function requestMovieUserRating(int $userId, int $movieId): ?MovieUserRating{
        $sql = "SELECT *  FROM `movies_users_ratings` WHERE `user_id` = $userId AND `movie_id` = $movieId;";
        $rows = $this->_getRows(HOST, DB_NAME, LOGIN, PASSWORD, $sql);
        if (count($rows) == 1) {
            $value = $rows[0];
            $movieUserRating = new MovieUserRating($value["id"], $value["user_id"], $value["movie_id"], $value["rate"]);
            return $movieUserRating;
        } else if(count($rows) > 1){
            echo "Plus d'un MovieUserRating trouvé...";
            return NULL;
        } else {
            return NULL;
        }
    }

    public function setMovieUserRating(int $userId, int $movieId, int $rate){
        $sql = "INSERT INTO `movies_users_ratings` (`user_id`, `movie_id`, `rate`) VALUES ('$userId', '$movieId', '$rate')";
        $this->_getRows(HOST, DB_NAME, LOGIN, PASSWORD, $sql);
    }

    public function updateMovieUserRating(int $movieUserRatingId, int $rate){
        $sql = "UPDATE `movies_users_ratings` SET `rate` = '$rate' WHERE `movies_users_ratings`.`id` = $movieUserRatingId";
        $this->_getRows(HOST, DB_NAME, LOGIN, PASSWORD, $sql);
    }

    public function getLoginStatus(){return $this->_loginStatus;}
    public function getMovies(){return $this->_movies;}
    public function getMovie(){return $this->_movie;}

}
?>