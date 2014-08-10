<?php

/* 
 * @author Daniel Condie <daniel.condie18@gmail.com>
 */

class MatchesController extends AppController {
    public $currentPage = 'matches';
    public $uses = array('Match', 'User');
    public $paginate = array(
        'limit' => 25,
        'order' => array(
            'Match.datePlayed' => 'desc',
        ),
    );
    
    public function index() {
        $this->Paginator->settings = $this->paginate;
        $matches = $this->Paginator->paginate();
        $this->set(compact('matches'));
    }
    
    public function add() {
        if ($this->request->is('post')) {
            $this->Match->create();
            if ($this->Match->saveAll($this->request->data)) {
                $this->Session->setFlash('Match successfully created.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Match creation unsuccessful. Please try again.', 'error');
            }
        }
        
        $scoringSystems = $this->Match->ScoringSystem->find('list');
        $participants = $this->User->find('list');
        $this->set(compact('scoringSystems', 'participants'));
    }
}