<?php

final class TournamentDataConverter {

    /**
     * Pfad und Name der zu verarbeitenden Datei
     * @var string
     */
    private $file;

    /**
     * Veranstaltungsore
     * @var array
     */
    private $places;

    /**
     * Mannschaften 
     * @var array
     */
    private $teams;

    /**
     * Gruppen 
     * @var array
     */
    private $groups;

    /**
     * Spiele 
     * @var array
     */
    private $matches;

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

        $this->places  = array();
        $this->teams   = array();
        $this->groups  = array();
        $this->matches = array();
    }

    public function convert () {
        $content = file($this->file);

        if ( ! (is_array($content) and count($content)) ) {
            throw new Exception('No data in ' . $this->file . ' found.');
        }

        foreach ( $content as $k => $v ) {
            $this->parse($v);
        }
    }

    public function getJson () {
        $json = array(
            'teams'   => $this->teams,
            'places'  => $this->places,
            'groups'  => $this->groups,
            'matches' => $this->matches,
        );

        print_r(json_encode($json));
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

        $this->matches[$data['match_id']] = $m;

        return $data['match_id'];
    }

    /**
     * erzeugt einen Gruppen-Eintrag
     *
     * @param string Gruppe
     * @return ID der Gruppe 
     */
    private function generateGroup( $group) {
        $prefix = "";

        if ( is_array($this->groups) and count($this->groups) ) {
            foreach ( $this->groups as $k => $v ) {
                if ( $v['name'] === $group) {
                    return $k;
                }
            }
        }

        $id = $group;

        $g = array(
            'name' => $group,
        );

        $this->groups[$id] = $g;

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

        if ( is_array($this->teams) and count($this->teams) ) {
            foreach ( $this->teams as $k => $v ) {
                if ( $v['name'] === $team) {
                    return $k;
                }
            }
        }

        $id = $prefix . count($this->teams);

        $t = array(
            'name' => $team,
        );

        $this->teams[$id] = $t;

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

        if ( is_array($this->places) and count($this->places) ) {
            foreach ( $this->places as $k => $v ) {
                if ( $v['name'] === $place ) {
                    return $k;
                }
            }
        }

        $id = $prefix . count($this->places);

        $p = array(
            'name' => $place,
        );

        $this->places[$id] = $p;

        return $id;
    }
}
