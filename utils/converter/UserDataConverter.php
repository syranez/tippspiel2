<?php

require('UserDataModel.php');

/**
 * konvertiert Userdaten aus dem tippspiel-Format ins tippspiel2-Format
 *
 * @throws Exception
 */
final class UserDataConverter {

    /**
     * Pfad und Name der zu verarbeitenden Datei
     *
     * @var string
     */
    private $file;

    /**
     * Userdatenmodel.
     *
     * @var UserDataModel
     */
    private $model;

    /**
     * Tournament-Data-Model
     *
     * @var TournamentDataModel
     */
    private $tournamentModel;

    /**
     * __construct
     *
     * @param string $file Pfad + Name der zu verarbeitenden Datei
     * @throws Exception
     */
    public function __construct ( $file ) {
        if ( ! file_exists($file) ) {
            throw new Exception('File not found.');
        }

        $this->file = $file;

        $this->model = new UserDataModel();
    }

    /**
     * setzt das Tournament-Model
     *
     * @param TournamentDataModel $model
     */
    public function setTournamentModel ( $model ) {
        $this->tournamentModel = $model;
    }

    public function convert () {
        $content = file($this->file);

        if ( ! (is_array($content) and count($content)) ) {
            throw new Exception('No data in ' . $this->file . ' found.');
        }

        $this->generateAccount(array(
            'login' => basename($this->file),
        ));

        foreach ( $content as $k => $v ) {
            $this->parse($v);
        }

        return $this->model;
    }

    private function parse ( $entry ) {
        $data = array_map("trim", explode(';', $entry));

        $home_team_id  = $this->tournamentModel->getHomeTeamIdByMatchId($data[0]);
        $guest_team_id = $this->tournamentModel->getGuestTeamIdByMatchId($data[0]);

        // [0] => 1;-;-;0;3
        $this->generateBet(array(
            'tournament_id' => $this->tournamentModel->getId(),
            'match_id'      => $data[0],
            'home_team_id'  => $home_team_id,
            'guest_team_id' => $guest_team_id,
            'goals'         => array(
                'regular'   => array(
                    $home_team_id  => $data[3],
                    $guest_team_id => $data[4],
                ),
            ),
        ));
    }

    /**
     * mappt die Accountdatan um.
     *
     */
    private function generateAccount ( $userdata ) {
        $a = array(
            'login' => $userdata['login'],
        );

        $this->model->setAcount($a);
    }

    private function generateBet ( $data ) {
        $prefix = "";

        $id = $data['match_id'];

        $b = array(
            'goals' => array(
                $data['home_team_id']  => $data['goals']['regular'][$data['home_team_id']],
                $data['guest_team_id'] => $data['goals']['regular'][$data['guest_team_id']],
            ),
        );

        $this->model->addBetByMatchAndTournamentId($data['tournament_id'], $id, $b);

        return $data['match_id'];
    }
}
