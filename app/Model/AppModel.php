<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    
    /**
     * Override default find behaviour to not retrieve soft-deleted records.
     */
    public function find($type = 'first', $query = array()) {
        if ($this->hasField('archived')) {
            $query['conditions'][$this->alias . '.archived'] = false;
        }
        return parent::find($type, $query);
    }
    
    public function findWithArchived($type = 'first', $query = array()){
        return parent::find($type, $query);
    }
    
    public function formatDateForDatabase($dateString) {
        $ret = '';
        if (!empty($dateString)) {
            $ret = date('Y-m-d', strtotime($dateString));
        }
        return $ret;
    }

    public function formatDateForDisplay($dateString) {
        $ret = '';
        if (!empty($dateString)) {
            $ret = date('d/m/Y', strtotime($dateString));
        }
        return $ret;
    }
}
