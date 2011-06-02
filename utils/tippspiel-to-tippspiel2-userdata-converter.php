<?php
/**
 * konvertiert die Usertipps für ein Tournament aus dem Tippspiel-Format in
 * das Tippspiel2-Format um.
 */

require('converter/TournamentDataConverter.php');
require('converter/UserDataConverter.php');

if ( $_SERVER['argc'] !== 3 ) {
    echo 'Usage: ./tippspiel-to-tippspiel2-data-converter.php PATH_TO_TIPPSPIEL_DATA_FILE PATH_TO_TIPPSPIEL_USERDATA_FILE' . "\n";
    die('');
}

$dir = '../data/players/' . $_SERVER['argv'][1];
if ( ! file_exists($dir) ) {
    mkdir($dir);
}

$file = basename($_SERVER['argv'][2]);

try {
    $dc = new TournamentDataConverter($_SERVER['argv'][1]);
    $tournament_model = $dc->convert();
} catch ( Exception $e ) {
    echo $e->getMessage() . "\n";
    die('');
}

try {
    $udc = new UserDataConverter($_SERVER['argv'][2]);
    $udc->setTournamentModel($tournament_model);
    $user_model = $udc->convert();

    file_put_contents($dir . '/' . $file, $user_model->getJson());
} catch ( Exception $e ) {
    echo $e->getMessage() . "\n";
    die('');
}
