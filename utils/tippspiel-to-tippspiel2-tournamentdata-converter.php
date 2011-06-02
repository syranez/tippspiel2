/**
 * konvertiert die Tournament-Daten aus dem Tippspiel-Format in
 * das Tippspiel2-Format um.
 */
<?php

require('converter/TournamentDataConverter.php');

if ( $_SERVER['argc'] !== 2 ) {
    echo 'Usage: ./tippspiel-to-tippspiel2-data-converter.php PATH_TO_TIPPSPIEL_DATA_FILE' . "\n";
    die('');
}

try {
    $dc = new TournamentDataConverter($_SERVER['argv'][1]);
    $tournament_model = $dc->convert();
    echo $tournament_model->getJson();
} catch ( Exception $e ) {
    echo $e->getMessage() . "\n";
    die('');
}
