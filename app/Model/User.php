<?php

/**
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

App::uses('Security', 'Utility');

class User extends AppModel {
    public $name = 'User';
    public $displayField = 'displayName';
    
    public $validate = array(
        'firstname' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'A first name is required.',
            ),
        ),
        'lastname' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'A last name is required.',
            ),
        ),
        'email' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'A valid email address is required.',
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'A valid email address is required.',
            ),
            'uniqueEmail' => array(
                'rule' => array('uniqueEmail'),
                'message' => 'A user with that email address already exists.',
            ),
        ),
        'password' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter a password.',
                'on' => 'create',
            ),
            'strongPassword' => array(
                'rule' => array('strongPassword'),
                'message' => 'Please enter a strong password containing between 6 and 12 characters, and at least one number as well as lower case and upper case letters.',
                'on' => 'create',
            ),
            'confirm' => array(
                'rule' => array('confirmPassword'),
                'message' => 'Please ensure that both password fields match.',
            ),
            'length' => array(
                'rule' => array('between', 6, 12),
                'message' => 'Password must be between 6 and 12 characters inclusive.',
            ),
        ),
    );
    
    public $hasMany = array(
        'HomeMatch' => array(
            'className' => 'Match',
            'foreignKey' => 'player1',
        ),
        'AwayMatch' => array(
            'className' => 'Match',
            'foreignKey' => 'player2',
        )
    );
    
    public $hasAndBelongsToMany = array(
        'Association' => array(
            'className' => 'Association',
            'joinTable' => 'associations_users',
            'foreignKey' => 'userId',
            'associationForeignKey' => 'associationId',
        ),
    );
    
    public function uniqueEmail() {
        $clash = $this->find('first', array(
            'conditions' => array(
                'User.email' => $this->data['User']['email'],
            ),
        ));
        return empty($clash);
    }
    
    public function beforeSave($options = array()) {
        if (empty($this->data['User']['displayName'])) {
            $this->data['User']['displayName'] = $this->data['User']['email'];
        }
        if (!empty($this->data['User']['password'])) {
            $this->data['User']['password'] = Security::hash($this->data['User']['password']);
        }
        return true;
    }
    
    /**
     * Checks if the supplied password is considered strong, meaning it consists of a string
     * with length between 6 and 12 (inclusive) and at least one number and letter of each case.
     */
    public function strongPassword() {
        $pw = $this->data['User']['password'];
        if (preg_match('/[A-Z]/', $pw) && preg_match('/[a-z]/', $pw) && preg_match('/[0-9]/', $pw)) {
            return true;
        }
        return false;
    }
    
    /**
     * Checks if the supplied password and confirm password fields match.
     */
    public function confirmPassword() {
        return ($this->data['User']['password'] == $this->data['User']['confirmPassword']);
    }
    
    public function getMatches($userId) {
        $matches = array();
        if (!empty($userId)) {
            $user = $this->find('first', array(
                'User.id' => $userId,
            ));
            if (!empty($user)) {
                $matches = array_merge($user['HomeMatch'], $user['AwayMatch']);
            }
        }
        return $matches;
    }
    
    
    public function generateValidationCode() {
        $code = $this->generateRandomString(6);
        $user = $this->find('first', array(
            'conditions' => array(
                'User.validation_code' => $code,
            )
        ));
        if (!empty($user)) {
            $code = $this->generateValidationCode();
        }
        return $code;
    }
    
    //SOURCE: http://stackoverflow.com/questions/4356289/php-random-string-generator
    public function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}