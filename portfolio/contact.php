<?php
$nom = "";
$email = "";
$tel = "";
$motif = "";
$creneau = "";
$premiere_demande = "";
$message = "";

$erreurs = [];
$formulaire_valide = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = isset($_POST["nom"]) ? trim($_POST["nom"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $tel = isset($_POST["tel"]) ? trim($_POST["tel"]) : "";
    $motif = isset($_POST["motif"]) ? trim($_POST["motif"]) : "";
    $creneau = isset($_POST["creneau"]) ? trim($_POST["creneau"]) : "";
    $premiere_demande = isset($_POST["premiere_demande"]) ? trim($_POST["premiere_demande"]) : "";
    $message = isset($_POST["message"]) ? trim($_POST["message"]) : "";

    if (empty($nom)) {
        $erreurs[] = "Le champ Nom est obligatoire et n'a pas été rempli.";
    } elseif (strlen($nom) < 2) {
        $erreurs[] = "Le champ Nom doit contenir au moins 2 caractères.";
    }

    if (empty($email)) {
        $erreurs[] = "Le champ Adresse de courriel est obligatoire et n'a pas été rempli.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse de courriel saisie n'est pas valide.";
    }

    if (empty($tel)) {
        $erreurs[] = "Le champ Numéro de téléphone n'a pas été rempli.";
    } elseif (!preg_match("/^[0-9+\s.-]{10,15}$/", $tel)) {
        $erreurs[] = "Le numéro de téléphone est mal rempli (10 chiffres attendus).";
    }

    $motifs_autorises = ["information", "stage", "projet", "autre"];
    if (empty($motif) || !in_array($motif, $motifs_autorises)) {
        $erreurs[] = "Veuillez sélectionner un motif de contact valide dans la liste.";
    }

    if (empty($creneau)) {
        $erreurs[] = "Veuillez préciser un créneau horaire (jour et horaire).";
    }

    if ($premiere_demande !== "Oui" && $premiere_demande !== "Non") {
        $erreurs[] = "Veuillez indiquer s'il s'agit de votre première demande.";
    }

    if (empty($message)) {
        $erreurs[] = "Le champ Message est obligatoire et n'a pas été rempli.";
    } elseif (strlen($message) < 5) {
        $erreurs[] = "Le message est trop court (minimum 5 caractères).";
    }

    if (empty($erreurs)) {
        $formulaire_valide = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="haut-header">
            <img src="pp.png" alt="Logo du site">
            <h1>Portfolio de Bouette Marc</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Accueil</a></li>
                <li><a href="apropos.html">A propos</a></li>
                <li><a href="activites.html">Mes activités</a></li>
                <li class="menu-deroulant">
                    <a href="#">Médias</a>
                    <ul class="sous-menu">
                        <li><a href="images.html">Images</a></li>
                        <li><a href="videos.html">Vidéos</a></li>
                    </ul>
                </li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="conteneur-principal">
        <h2>Me contacter</h2>

        <?php if (!empty($erreurs)): ?>
            <div class="bloc-erreurs">
                <h3>Veuillez corriger les erreurs suivantes :</h3>
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?php echo htmlspecialchars($erreur); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($formulaire_valide): ?>
            <div class="bloc-confirmation">
                <h3>Confirmation d'envoi</h3>
                <p>Votre demande a bien été prise en compte et va être étudiée.</p>
                <h4>Récapitulatif des informations envoyées :</h4>
                <ul>
                    <li><strong>Nom :</strong> <?php echo htmlspecialchars($nom); ?></li>
                    <li><strong>Adresse de courriel :</strong> <?php echo htmlspecialchars($email); ?></li>
                    <li><strong>Numéro de téléphone :</strong> <?php echo htmlspecialchars($tel); ?></li>
                    <li><strong>Motif de contact :</strong> <?php echo htmlspecialchars($motif); ?></li>
                    <li><strong>Créneau horaire :</strong> <?php echo htmlspecialchars($creneau); ?></li>
                    <li><strong>Première demande :</strong> <?php echo htmlspecialchars($premiere_demande); ?></li>
                    <li><strong>Message :</strong> <?php echo nl2br(htmlspecialchars($message)); ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <form action="contact.php" method="post">
            <div class="champ-formulaire">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
            </div>

            <div class="champ-formulaire">
                <label for="email">Adresse de courriel :</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="champ-formulaire">
                <label for="tel">Numéro de téléphone :</label>
                <input type="tel" id="tel" name="tel" value="<?php echo htmlspecialchars($tel); ?>">
            </div>

            <div class="champ-formulaire">
                <label for="motif">Motif de contact :</label>
                <select id="motif" name="motif">
                    <option value="">-- Choisissez un motif --</option>
                    <option value="information" <?php if ($motif === "information") echo "selected"; ?>>Demande d'information</option>
                    <option value="stage" <?php if ($motif === "stage") echo "selected"; ?>>Proposition de stage</option>
                    <option value="projet" <?php if ($motif === "projet") echo "selected"; ?>>Collaboration sur un projet</option>
                    <option value="autre" <?php if ($motif === "autre") echo "selected"; ?>>Autre</option>
                </select>
            </div>

            <div class="champ-formulaire">
                <label for="creneau">Créneau horaire (jour et heure) :</label>
                <input type="datetime-local" id="creneau" name="creneau" value="<?php echo htmlspecialchars($creneau); ?>">
            </div>

            <div class="champ-formulaire">
                <label for="premiere_oui">Est-ce votre première demande ?</label>
                <input type="radio" id="premiere_oui" name="premiere_demande" value="Oui" <?php if ($premiere_demande === "Oui") echo "checked"; ?>>
                <label for="premiere_oui" class="label-inline">Oui</label>

                <input type="radio" id="premiere_non" name="premiere_demande" value="Non" <?php if ($premiere_demande === "Non") echo "checked"; ?>>
                <label for="premiere_non" class="label-inline">Non</label>
            </div>

            <div class="champ-formulaire">
                <label for="message">Message :</label>
                <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($message); ?></textarea>
            </div>

            <div class="boutons-formulaire">
                <button type="submit">Envoyer</button>
                <button type="reset">Remettre à l'état initial</button>
            </div>
        </form>
    </div>

    <footer>
        <p>Bouette Marc - Groupe 4 - <a href="mailto:Marc.Bouette@etu.uca.fr">Marc.Bouette@etu.uca.fr</a></p>
    </footer>

</body>
</html>