<?php

/* 
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

class WinningCondition extends AppModel {
    public $name = 'WinningCondition';
    public $displayField = 'name';
    
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter a name for the winning condition.',
            ),
        ),
    );
    
    /**
     * Determines which of the passed scores are the winner(s).
     * @param type $scores - an array containing playerId => score for each player
     * @param type $limit - the limit property of the scoring system
     */
    public function determineWinners($scores, $limit) {
        
        switch ($this->data['WinningCondition']['name']) {
            case 'Best Of':
                $winners = $this->determineWinnersBestOf($scores, $limit);
                break;
            case 'First To':
                $winners = $this->determineWinnersFirstTo($scores, $limit);
                break;
            case 'Timed':
                $winners = $this->determineWinnersTimed($scores);
                break;
        }
        return $winners;
    }
    
    /**
     * In 'first to' scoring, the winner(s) are the player(s)
     * whose score(s) are equal to the limit
     */
    private function determineWinnersFirstTo($scores, $limit) {
        return array_keys($scores, $limit);
    }
    
    /**
     * In 'best of' scoring, the winner(s) are the player(s) who win an amount
     * of games/sets equal to the mathematical ceiling of half the limit.
     */
    private function determineWinnersBestOf($scores, $limit) {
        $winningScore = ceil($limit/2);
        return array_keys($scores, $winningScore);
    }

    /**
     * In timed scoring, the winner(s) are the player(s) who have the highest score
     * at the end of the time limit.
     */
    private function determineWinnersTimed($scores) {
        return array_keys(max($scores));
    }
}