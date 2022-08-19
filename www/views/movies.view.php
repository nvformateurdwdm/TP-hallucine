<?php

ob_start();

?>

<div id="items" class="row">

<?php
for($i=0; $i < count($movies);$i++) : 
?>

<div class="item col">
    <a href="index.php?page=movie&movieid=<? $movies[$i]->getId(); ?>">
        <img src="image/<?= $movies[$i]->getImageUrl(); ?> ">
        <br>
        <?= $movies[$i]->getTitle(); ?>
        <br>
        <?= $movies[$i]->getRelaseDate()->format("Y"); ?>
    </a>
</div>
    
<?php endfor; ?>

</div>

<?php
$content = ob_get_clean();
$pageTitle = "Halluciné - Films";
require "template.view.php";
?>