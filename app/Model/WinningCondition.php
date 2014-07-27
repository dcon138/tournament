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
}