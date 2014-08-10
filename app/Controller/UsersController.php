<?php

/* 
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

class UsersController extends AppController {
    public $currentPage = 'users';
    public $uses = array('User');
    public $paginate = array(
        'limit' => 25,
        'order' => array(
            'User.created' => 'desc',
        ),
    );
    
    public function index() {
        $this->Paginator->settings = $this->paginate;
        $users = $this->Paginator->paginate();
        $this->set(compact('users'));
    }
    
    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            $this->request->data['User']['validation_code'] = $this->User->generateValidationCode();
            $this->User->sendValidationEmail();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('User successfully created.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('User creation unsuccessful. Please try again.', 'error');
            }
        }
    }
    
    public function login() {
        if ($this->request->is('post')) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.email' => $this->request->data['User']['email'],
                    'User.password' => Security::hash($this->request->data['User']['password']),
                ),
            ));
            if (empty($user)) {
                $this->Session->setFlash('Your username / password combination was incorrect.', 'alert');
            } else if (!$user['User']['validated']) {
                $this->Session->setFlash('You must validate your account prior to using the site.', 'alert');
            } else if ($this->Auth->login($user)) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('Login was unsuccessful. Please try again.', 'alert');
            }
        }
    }
    
    public function logout() {
        $this->redirect($this->Auth->logout());
    }
}