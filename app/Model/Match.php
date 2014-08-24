<?php

/* 
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

class Match extends AppModel {
    public $name = 'Match';
    public $displayField = 'datePlayed';
    
    public $validate = array(
        'scoringSystemId' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Scoring System must be selected.',
            ),
            'validScoringSystem' => array(
                'rule' => array('validScoringSystem'),
                'message' => 'Please select a valid scoring system.',
            ),
        ),
        'affectsRating' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please indicate if this match affects players\' ratings.',
            ),
            'boolean' => array(
                'rule' => array('boolean'),
                'message' => 'Please indicate if this match affects players\' ratings by selecting from the provided menu',
            ),
        ),
        'datePlayed' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please indicate the date this match was played.',
            ),
        ),
    );
    
    public $belongsTo = array(
        'ScoringSystem' => array(
            'className' => 'ScoringSystem',
            'foreignKey' => 'scoringSystemId',
        ),
    );
    
    public $hasAndBelongsToMany = array(
        'Participant' => array(
            'className' => 'User',
            'joinTable' => 'match_players',
            'foreignKey' => 'match_id',
            'associationForeignKey' => 'user_id',
        ),
    );
    
    public function validPlayer($check) {
        return ($this->Player1->hasAny(array('Player1.id' => reset($check))));
    }
    
    public function validScoringSystem() {
        return $this->ScoringSystem->hasAny(array('ScoringSystem.id' => $this->data['Match']['scoringSystemId']));
    }
    
    /**
     * Calculates the winner(s) of this match, stores them in the database
     * and returns the list of winners.
     * @return array - the list of $playerId of the winners of the match
     */
    public function determineWinners() {
        $this->ScoringSystem->read(null, $this->data['Match']['scoringSystemId']);
        $winners = $this->ScoringSystem->determineWinners($this->getScores());
        
        $players = $this->MatchPlayer->find('all', array(
            'conditions' => array(
                'MatchPlayer.match_id' => $this->id,
            ),
        ));
        foreach ($players as $player) {
            $this->MatchPlayer->id = $player['MatchPlayer']['id'];
            $winner = false;
            if (in_array($player['MatchPlayer']['user_id'], $winners)) {
                $winner = true;
            }
            $this->MatchPlayer->saveField('winner', $winner);
        }
        return $winners;
    }
    
    public function getWinners() {
        return $this->MatchPlayer->find('all', array(
            'MatchPlayer.match_id' => $this->id,
            'MatchPlayer.winner' => true,
        ));
    }
    
    public function getPlayers() {
        return $this->MatchPlayer->find('all', array(
            'MatchPlayer.match_id' => $this->id,
        ));
    }
    
    /**
     * Gets all players for the current match, and their score.
     * @return array - a list of players in the match and their scores (player_id => score format)
     */
    private function getScores() {
        $players = $this->MatchPlayer->find('all', array(
            'MatchPlayer.match_id' => $this->data['Match']['id'],
        ));
        
        $scores = array();
        foreach ($players as $player) {
            $scores[$player['MatchPlayer']['user_id']] = $player['MatchPlayer']['score'];
        }
        return $scores;
    }
    
    public function beforeSave($options = array()) {
        if (!empty($this->data['Match']['datePlayed'])) {
            //swap the date and month around to keep the date functions happy, they want mm/dd/yyyy format
            $words = explode('/', $this->data['Match']['datePlayed']);
            $this->data['Match']['datePlayed'] = $words[1] . '/' . $words[0] . '/' . $words[2];
            $this->data['Match']['datePlayed'] = $this->formatDateForDatabase($this->data['Match']['datePlayed']);
        }
        return true;
    }
    
    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if (!empty($results[$key]['Match']['datePlayed'])) {
                $results[$key]['Match']['datePlayed'] = $this->formatDateForDisplay($results[$key]['Match']['datePlayed']);
            }
        }
        return $results;
    }
}