<?php

final class TournamentModel {

    /**
     * Veranstaltungsorte
     *
     * @var array
     */
    private $places;

    /**
     * Mannschaften 
     *
     * @var array
     */
    private $teams;

    /**
     * Gruppen 
     *
     * @var array
     */
    private $groups;

    /**
     * Spiele 
     *
     * @var array
     */
    private $matches;

    /**
     * __construct
     *
     */
    public function __construct () {
        $this->places  = array();
        $this->teams   = array();
        $this->groups  = array();
        $this->matches = array();
    }

    /**
     * gibt die migrierten Daten als JSON zurück.
     *
     */
    public function getJson () {
        return json_encode(array(
            'teams'   => $this->teams,
            'places'  => $this->places,
            'groups'  => $this->groups,
            'matches' => $this->matches,
        ));
    }





    public function addMatchById ( $id, array $match ) {
        $this->matches[$id] = $match;
    }

    public function getMatchById ( $id ) {
        return $this->matches[$id];
    }

    public function getMatchCount () {
        return count($this->matches);
    }





    public function addGroupById ( $id, array $group ) {
        $this->groups[$id] = $group;
    }

    public function getGroupIdByName ( $name ) {
        if ( is_array($this->groups) and count($this->groups) ) {
            foreach ( $this->groups as $k => $v ) {
                if ( $v['name'] === $name ) {
                    return $k;
                }
            }
        }

        return null;
    }

    public function getGroupCount () {
        return count($this->groups);
    }





    public function addTeamById ( $id, array $team ) {
        $this->teams[$id] = $team;
    }

    public function getTeamIdByName ( $name ) {
        if ( is_array($this->teams) and count($this->teams) ) {
            foreach ( $this->teams as $k => $v ) {
                if ( $v['name'] === $name ) {
                    return $k;
                }
            }
        }

        return null;
    }

    public function getTeamCount () {
        return count($this->teams);
    }





    public function addPlaceById ( $id, array $place ) {
        $this->places[$id] = $place;
    }

    public function getPlaceIdByName ( $name ) {
        if ( is_array($this->places) and count($this->places) ) {
            foreach ( $this->places as $k => $v ) {
                if ( $v['name'] === $name ) {
                    return $k;
                }
            }
        }

        return null;
    }

    public function getPlaceCount () {
        return count($this->places);
    }
}
