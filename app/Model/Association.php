<?php

/* 
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

class Association extends AppModel {
    public $name = 'Association';
    public $displayField = 'name';
    
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter a name for the Association.',
            ),
        ),
    );
    
    public $hasAndBelongsToMany = array(
        'User' => array(
            'className' => 'User',
            'joinTable' => 'associations_users',
            'foreignKey' => 'associationId',
            'associationForeignKey' => 'userId',
        ),
    );
}