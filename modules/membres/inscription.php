<?php

	// Ne pas oublier d'inclure la librarie Form
	include CHEMIN_LIB.'form.php';

	// "formulaire_inscription" est l'ID unique du formulaire
	$form_inscription = new Form('formulaire_inscription');
	$form_inscription->method('POST');

	$form_inscription->add('Text', 'nom_utilisateur')
					 ->label("Votre nom d'utilisateur");
	
	$form_inscription->add('Password', 'mdp')
					 ->label("Votre mot de passe");
	
	$form_inscription->add('Password', 'mdp_verif')
					 ->label("Votre mot de passe (vérification)");
	
	$form_inscription->add('Email', 'adresse_email')
					 ->label("Votre adresse email");
	
	$form_inscription->add('File', 'avatar')
					 ->filter_extensions('jpg', 'png', 'gif')
					 ->max_size(8192) // 8 Kb
					 ->label("Votre avatar (facultatif)")
					 ->Required(false);
	
	$form_inscription->add('Submit', 'submit')
					 ->value("Je veux m'inscrire !");
	
	// Pré-remplissage avec les valeurs précédemment entrées (s'il y en a)
	$form_inscription->bound($_POST);

	// Creation d'un tableau des erreurs
	$erreurs_inscription = array();

	// Validation des champs suivant les règles en utilisant les données du tableau $_POST
	if ($form_inscription->is_valid($_POST)) {

		// On vérifie si les deux mots de passe correspondent
		if ($form_inscription->get_cleaned_data('mdp') != 
		$form_inscription->get_cleaned_data('mdp_verif')) {
			$erreurs_inscription[] = "Les deux mots de passes entrés sont différents !";
		}

		// Si d'autres erreurs ne sont pas survenues
		if (empty($erreurs_inscription)) {

			// Tiré de la documentation PHP sur <http://fr.php.net/uniqid>
			$hash_validation = md5(uniqid(rand(), true));

			// Tentative d'ajout du membre dans la base de données
			list($nom_utilisateur, $mot_de_passe, $adresse_email, $avatar) =
			$form_inscription->get_cleaned_data('nom_utilisateur', 'mdp', 'adresse_email', 'avatar');

			// On veut utiliser le modèle de l'inscription (~/modeles/inscription.php)
			include CHEMIN_MODELE.'inscription.php';

			// ajouter_membre_dans_bdd() est défini dans ~/modeles/inscription.php
			$id_utilisateur = ajouter_membre_dans_bdd($nom_utilisateur, sha1($mot_de_passe),
			$adresse_email, $hash_validation);

			// Si la base de données a bien voulu ajouter l'utilisateur (pas de doublons)
			if (ctype_digit($id_utilisateur)) {

				// On transforme la chaine en entier
				$id_utilisateur = (int) $id_utilisateur

				// Préparation du mail
				$message_mail = '<html><head></head><body>
				<p>Merci de vous être inscrit sur "mon site" !</p>
				<p>Veuillez cliquer sur <a href="' . $_SERVER['PHP_SELF'] . '?
				module=membres&amp;action=valider_compte&amp;hash=' . $hash_validation . '">ce
				lien</a> pour activer votre compte !</p>
				</body></html>';

				$headers_mail  = 'MIME-Version: 1.0'                              . "\r\n";
				$headers_mail .= 'Content-type: text/html; charset=utf-8'         . "\r\n";
			}

		} else {
			// On affiche à nouveau le formulaire d'inscription
			include CHEMIN_VUE.'formulaire_inscription.php';
		}

	} else {
		// On affiche à nouveau le formulaire d'inscription
		include CHEMIN_VUE.'formulaire_inscription.php';
	}

