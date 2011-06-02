/**
 * konvertiert die Usertipps für ein Tournament aus dem Tippspiel-Format in
 * das Tippspiel2-Format um.
 */
<?php

require('converter/TournamentDataConverter.php');
require('converter/UserDataConverter.php');

if ( $_SERVER['argc'] !== 3 ) {
    echo 'Usage: ./tippspiel-to-tippspiel2-data-converter.php PATH_TO_TIPPSPIEL_DATA_FILE PATH_TO_TIPPSPIEL_USERDATA_FILE' . "\n";
    die('');
}

try {
    $dc = new UserDataConverter($_SERVER['argv'][1], $_SERVER['argv'][2]);
    $dc->convert();
    $dc->getJson();
} catch ( Exception $e ) {
    echo $e->getMessage() . "\n";
    die('');
}
