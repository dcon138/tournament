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
    
    public $hasAndBelongsToMany = array(
        'Association' => array(
            'className' => 'Association',
            'joinTable' => 'associations_users',
            'foreignKey' => 'userId',
            'associationForeignKey' => 'associationId',
        ),
        'Match' => array(
            'className' => 'Match',
            'joinTable' => 'match_players',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'match_id',
        ),
    );
    
    /**
     * Override default find behaviour to not retrieve users that have not
     * validated their account.
     */
    public function find($type = 'first', $query = array()) {
        $query['conditions']['validated'] = true;
        return parent::find($type, $query);
    }
    
    public function findWithArchived($type = 'first', $query = array()){
        $query['conditions']['validated'] = true;
        return parent::find($type, $query);
    }
    
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
    
    public function sendValidationEmail() {
        //TODO 
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