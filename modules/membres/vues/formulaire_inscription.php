<h2>Inscription au site</h2>

<?php

if (!empty($erreurs_inscriptions)) {
	echo '<ul>' . "\n";
	foreach ($erreurs_inscriptions as $e) {
		echo ' <li>' . $e . '</li>' . "\n";
	}
	echo '</ul>';
}

echo $form_inscription;