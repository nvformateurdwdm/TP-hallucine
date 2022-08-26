<?php

ob_start();

if (isset($user)) {
    $userId = strval($user->getId());
    $movieId = strval($movie->getId());
    if(isset($movieUserRating)){
        $action = HallucineModel::MOVIE_USER_UPDATE_RATE;
        // $rate =   
    }else {
        $action = HallucineModel::MOVIE_USER_RATE;
    }
}

$sGenres = "";
$count = count($movie->getGenres());
foreach ($movie->getGenres() as $key => $value) {
    $sGenre = "<a href='index.php?page=genre&genreid=".$value->getId()."'>".$value->getName()."</a>";
    // $sGenre = $value->getName();
    $sGenres .= $sGenre;
    if($key < $count - 1){
        $sGenres .= ", ";
    }
}

$sActors = "";
$count = count($movie->getCastings()["3"]);
foreach ($movie->getCastings()["3"] as $key => $value) {
    $sActor = "<a href='index.php?page=casting&castingid=".$value->getId()."'>".$value->getFirstname(). " ". $value->getLastname() ."</a>";
    // $sGenre = $value->getName();
    $sActors .= $sActor;
    if($key < $count - 1){
        $sActors .= ", ";
    }
}

?>

<section id="movie_section">
    <div id="movie_section_content">
        <div id="movie_section_content_left">
            <img src="<?= IMAGE_PATH.$movie->getImageUrl(); ?>" alt="<?= $movie->getTitle(); ?>">
            <form id="form_rate" action="" method="post" style="display:<?= isset($user) ? "block" : "none"; ?>" >
                <input type="<?= IS_DEBUG ? "text" : "hidden"; ?>" name="userId" value="<?= $userId; ?>">
                <input type="<?= IS_DEBUG ? "text" : "hidden"; ?>" name="movieId" value="<?= $movieId; ?>">
                <input style="display:<?= isset($movieUserRating) ? "block" : "none"; ?>" type="<?= IS_DEBUG ? "text" : "hidden"; ?>" name="movieUserRatingId" value="<?= isset($movieUserRating) ? $movieUserRating->getId() : "" ?>">
                <input type="<?= IS_DEBUG ? "text" : "hidden"; ?>" name="action" value="<?= $action ?>">
                <input type="number" placeholder="Noter ce film." name="rate" pattern="^([0-9]|[1-9][0-9]|100)$" value="<?= isset($movieUserRating) ? $movieUserRating->getRate() : (IS_DEBUG ? random_int(5, 80) : "") ; ?>">
                <input type="submit" id='submit' value="<?= isset($movieUserRating) ? "Update Rate" : "Rate"; ?>" >
            </form>
        </div>
        <div id="movie_section_content_right">
            <h2><?= $movie->getTitle(); ?></h2>
            <h3><?= $movie->getReleaseDate()->format("d-m-Y"); ?></h3>
            <div id="rating" data-attr="<?= $movie->getRate() ?>">
                <div id="progressBar"></div>
            </div>
            <h4>Genre : <?= $sGenres ?></h4>
            <h4>Durée : <?= $movie->getformatedRuntime(); ?></h4>
            <h4>Acteurs : <?= $sActors ?></h4>
            <h4>Scénariste : Kad Merad, Marina Foïs</h4>
            <h4>Réalisateur : Kad Merad, Marina Foïs</h4>
            <h3>Résumé :<br> <?= $movie->getDescription(); ?> </h3>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
$pageTitle = "Halluciné - ".$movie->getTitle();
$idBodyCss = "movie";
$displayList = false;
require "template.view.php";
?>