<?php

require_once "models/HallucineModel.class.php";

class HallucineController{
    private $_hallucineModel;

    public function __construct(){
        $this->_hallucineModel = new HallucineModel;
    }

    public function showLogin(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = $this->_hallucineModel->requestLogin($_POST["email"], $_POST["password"]);
            $loginStatus = $this->_hallucineModel->getLoginStatus();
            switch ($loginStatus) {
                case HallucineModel::LOGIN_USER_NOT_FOUND:
                case HallucineModel::LOGIN_INCORRECT_PASSWORD:
                    require "views/login.view.php";
                    break;
                case HallucineModel::LOGIN_OK:
                    $_SESSION['user'] = serialize($user);
                    // var_dump($_SESSION['user']);
                    $this->showMovies();
                    break;
                default:
                    "Cas non géré...";
                    break;
            }
        } else {
            require "views/login.view.php";
        }
        
    }

    public function showMovies(int $sort = HallucineModel::SORT_MOVIES_BY_TITLE){
        $hm = $this->_hallucineModel;
        $hm->requestMovies($sort);
        $movies = $hm->getMovies();
        require "views/movies.view.php";
    }

    public function showMovie(int $movieId){
        $this->_hallucineModel->requestMovie($movieId);
        $movie = $this->_hallucineModel->getMovie();

        if(isset($_SESSION['user'])){
            $user = unserialize($_SESSION['user']);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            switch ($_POST['action']) {
                case HallucineModel::MOVIE_USER_RATE:
                    $this->_hallucineModel->setMovieUserRating($_POST['userId'], $_POST['movieId'], $_POST['rate']);
                    break;
                case HallucineModel::MOVIE_USER_UPDATE_RATE:
                    $this->_hallucineModel->updateMovieUserRating($_POST['movieUserRatingId'], $_POST['rate']);
                    break;
                default:
                    echo "cas de rating non géré...";
                    break;
            }
            $movieUserRating = $this->_hallucineModel->requestMovieUserRating($user->getId(), $movie->getId());
        } else {
            if(isset($user)){
                $movieUserRating = $this->_hallucineModel->requestMovieUserRating($user->getId(), $movie->getId());
            }
        }
        

        require "views/movie.view.php";
    }
}

?>