<?php
ob_start();
?>

<?php
$pageTitle = "TP Halluciné - Login";
include "head.php";
$idBodyCss = "login-registration";
?>

<?php
// $email = IS_DEBUG ? "nicolas.vedrine@gmail.com" : "";
$email = IS_DEBUG ? "alex.hubert@gmail.com" : "";
$password = IS_DEBUG ? "1234" : "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = "";
}
?>

<body id=<?= "\"".$idBodyCss. "\"" ?> >
    <section id="login-registration_section">
        <div id="login-registration_section_content">
            <form id="login" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]."?page=login") ?>" method="POST">
                <h1>Connexion</h1>
    
                <label><b>email</b></label>
                <input type="text" placeholder="Votre email" name="email" required value=<?= $email ?> >

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Votre mot de passe" name="password" required value=<?= $password ?> >

                <input type="submit" id='submit' value='login' >

                <a href="#">S'inscrire.</a>
                <a href="index.php?page=movies">Continuer sans s'identifier.</a>

                <?php
                    if(isset($loginStatus)){
                        $error = "";
                        switch ($loginStatus) {
                            case HallucineModel::LOGIN_USER_NOT_FOUND:
                                $error = "Utilisateur introuvable.";
                                break;
                            case HallucineModel::LOGIN_INCORRECT_PASSWORD:
                                $error = "Mot de passe incorrect.";
                                break;
                            default:
                                $error = "Erreur inconnue...";
                                break;
                        }
                        if ($error != "") {
                            echo "<p class='error'>$error</p>";
                        }
                    }
                ?>
                
            </form>
        </div>
    </section>
</body>

<?php
$content = ob_get_clean();


?>

<?= $content ?>
</html>
