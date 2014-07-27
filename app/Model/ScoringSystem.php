<?php

/* 
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

class ScoringSystem extends AppModel {
    public $name = 'ScoringSystem';
    public $displayField = 'name';
    
    public $validate = array(
        'limit' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Limit value must contain numbers only.',
            ),
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please define the scoring systems limiting score.',
            ),
        ),
        'winningConditionId' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please select a winning condition.',
            ),
            'validWinningCondition' => array(
                'rule' => array('validWinningCondition'),
                'message' => 'Please select a valid winning condition from the provided menu.',
            ),
        ),
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter a name for the scoring system.',
            ),
        ),
    );
    
    public $belongsTo = array(
        'WinningCondition' => array(
            'className' => 'WinningCondition',
            'foreignKey' => 'winningConditionId',
        ),
    );
    
    public function validWinningCondition() {
        if (!empty($this->data['winningConditionId'])) {
            $winningCondition = $this->WinningCondition->find('first', array(
                'WinningCondition.id' => $this->data['winningConditionId'],
            ));
            
            if (!empty($winningCondition)) {
                return true;
            }
        }
        return false;
    }
}

