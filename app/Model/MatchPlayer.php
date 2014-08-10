<?php 
/**
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

class MatchPlayer extends AppModel {
    public $name = 'MatchPlayer';
    public $displayField = 'user_id';
    
    public $validate = array(
        'match_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'match_id is required.',
            ),
            'validMatch' => array(
                'rule' => array('validMatch'),
                'message' => 'Selected match was invalid.',
            ),
        ),
        'user_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'user_id is required.',
            ),
            'validUser' => array(
                'rule' => array('validUser'),
                'message' => 'Selected user was invalid.',
            ),
        ),
    );
    
    public $belongsTo = array(
        'Match' => array(
            'className' => 'Match',
            'foreignKey' => 'match_id',
        ),
        'Player' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
    );
    
    public function validMatch($check) {
        return $this->Match->hasAny(array(
            'Match.id' => reset($check),
        ));
    }
    
    public function validUser($check) {
        return $this->Player->hasAny(array(
            'Player.id' => reset($check),
        ));
    }
}

