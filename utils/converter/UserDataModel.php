<?php

final class UserDataModel {

    /**
     * Accountdaten
     *
     * @var array
     */
    private $account;

    /**
     * Tipps des Spielers 
     *
     * @var array
     */
    private $bets;

    /**
     * __construct
     *
     */
    public function __construct () {
        $this->account = array();
        $this->bets    = array();
    }

    public function getJson () {
        return json_encode(array(
            'account' => $this->account,
            'bets'    => $this->bets,
        ));
    }

    /**
     * setzt die Accountdaten
     *
     * @param array $account
     */
    public function setAcount ( array $account ) {
        $this->account = $account;
    }

    /**
     * setzt einen Tipp für eine Spiel eines Tournaments
     *
     * @param string $tournament_id
     * @param string $match_id
     * @param array Tipp
     */
    public function addBetByMatchAndTournamentId( $tournament_id, $match_id, $bet ) {
        if ( (isset($this->bets[$tournament_id]))
             and ( ! is_array($this->bets[$tournament_id])) ) {
            $this->bets[$tournament_id] = array();
        }

        $this->bets[$tournament_id][$match_id] = $bet;
    }
}
