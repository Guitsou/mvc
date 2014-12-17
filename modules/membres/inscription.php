<div id="menu">
	
		<h2>Menu</h2>
		
		<ul>
			<li><a href="index.php">Accueil</a></li>
		</ul>
		
		<h3>Espace membre</h3>
		<ul>
			<li><a href="index.php?module=membres&amp;action=inscription">Inscription</a></li>
		</ul>
		
</div>

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