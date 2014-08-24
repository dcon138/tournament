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
    
    public function ajaxDetermineWinners() {
        $this->autoRender = false;

        $ret = array('response_status' => 'ERROR');

        if ($this->request->is('post')) {
            if (empty($this->request->data['match_id'])) {
                $ret['reason'] = 'Invalid match specified. Please try again.';
            } else {
                $match_id = $this->request->data['match_id'];
                if (!$this->Match->exists($match_id)) {
                    $ret['reason'] = 'Invalid match specified. Please try again.';
                } else {
                    $this->Match->read(null, $match_id);
                    $winners = $this->Match->determineWinners();
                    $winningPlayers = $this->Match->Participant->find('list', array(
                        'conditions' => array(
                            'Participant.id' => $winners,
                        ),
                    ));
                    $ret = array(
                        'response_status' => 'OK',
                        'data' => array(
                            'match_id' => $match_id,
                            'winners' => implode(' ', $winningPlayers),
                        )
                    );
                }
            }
        }
        return json_encode($ret);
    }
}