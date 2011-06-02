<?php

require('TournamentModel.php');

final class TournamentDataConverter {

    /**
     * Pfad und Name der zu verarbeitenden Datei
     * @var string
     */
    private $file;

    /**
     * Tournament-Model
     *
     * @var TournamentModel
     */
    private $model;

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

        $this->model = new TournamentModel();

        $this->model->setId(basename($file));
    }

    public function convert () {
        $content = file($this->file);

        if ( ! (is_array($content) and count($content)) ) {
            throw new Exception('No data in ' . $this->file . ' found.');
        }

        foreach ( $content as $k => $v ) {
            $this->parse($v);
        }

        return $this->model;
    }

    private function parse ( $entry ) {
        $data = array_map("trim", explode(';', $entry));

        $group_id      = $this->generateGroup($data[1]);
        $place_id      = $this->generatePlace($data[4]);
        $home_team_id  = $this->generateTeam($data[5]);
        $guest_team_id = $this->generateTeam($data[6]);

        // [63] => 64;FINAL;11.07.2010;20:30;Johannesburg;Niederlande;Spanien;0;1
        $this->generateMatch(array(
            'match_id'      => $data[0],
            'group_id'      => $group_id,
            'date'          => $data[2],
            'time'          => $data[3],
            'place_id'      => $place_id,
            'home_team_id'  => $home_team_id,
            'guest_team_id' => $guest_team_id,
            'goals'         => array(
                'regular'   => array(
                    $home_team_id  => $data[7],
                    $guest_team_id => $data[8],
                ),
            ),
        ));
    }

    private function generateMatch ( $data ) {
        $prefix = "";

        $m = array(
            'group' => $data['group_id'],
            'home'  => $data['home_team_id'],
            'guest' => $data['guest_team_id'],
            'date'  => $data['date'],
            'time'  => $data['time'],
            'place' => $data['place_id'],
            'goals' => $data['goals'],
        );

        $this->model->addMatchById($data['match_id'], $m);

        return $data['match_id'];
    }

    /**
     * erzeugt einen Gruppen-Eintrag
     *
     * @param string Gruppe
     * @return ID der Gruppe 
     */
    private function generateGroup( $group ) {
        $prefix = "";

        $id = $this->model->getGroupIdByName($group);
        if ( ! is_null($id) ) {
            return $id;
        }

        $id = $group;

        $g = array(
            'name' => $group,
        );

        $this->model->addGroupById($id, $g);

        return $id;
    }

    /**
     * erzeugt einen Team-Eintrag
     *
     * @param string Teamname
     * @return ID des Teams
     */
    private function generateTeam ( $team ) {
        $prefix = "team-";

        $id = $this->model->getTeamIdByName($team);
        if ( ! is_null($id) ) {
            return $id;
        }

        $id = $prefix . $this->model->getTeamCount();

        $t = array(
            'name' => $team,
        );

        $this->model->addTeamById($id, $t);

        return $id;
    }

    /**
     * erzeugt einen Place-Eintrag
     *
     * @param string Spielort
     * @return ID des Spielorts
     */
    private function generatePlace ( $place ) {
        $prefix = "place-";

        $id = $this->model->getPlaceIdByName($place);
        if ( ! is_null($id) ) {
            return $id;
        }

        $id = $prefix . $this->model->getPlaceCount();

        $p = array(
            'name' => $place,
        );

        $this->model->addPlaceById($id, $p);

        return $id;
    }
}
