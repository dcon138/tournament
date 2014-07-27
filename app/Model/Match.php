<?php

/* 
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

class Match extends AppModel {
    public $name = 'Match';
    public $displayField = 'datePlayed';
    
    public $validate = array(
        'player1' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Player 1 must be selected.',
            ),
        ),
        'player2' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Player 2 must be selected.',
            ),
        ),
        'scoringSystem' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Scoring System must be selected.',
            ),
        ),
        'player1Score' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Player 1 score must be enterred.',
            ),
        ),
        'player2Score' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Player 2 score must be enterred.',
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
        'Player1' => array(
            'className' => 'User',
            'foreignKey' => 'player1',
        ),
        'Player2' => array(
            'className' => 'User',
            'foreignKey' => 'player2',
        ),
        'ScoringSystem' => array(
            'className' => 'ScoringSystem',
            'foreignKey' => 'scoringSystem',
        ),
    );
}