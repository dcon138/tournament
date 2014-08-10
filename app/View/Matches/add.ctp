<?= $this->Form->create('Match') ?>
<?= $this->Form->input('datePlayed', array('type' => 'text', 'class' => 'datepicker')) ?>
<?= $this->Form->input('ScoringSystem', array('name' => 'data[Match][scoringSystemId]')) ?>
<?= $this->Form->input('Player 1', array('empty' => false, 'id' => 'participant1', 'options' => $participants, 'name' => 'Participant[]')) ?>
<?= $this->Form->input('Player 2', array('empty' => false, 'id' => 'participant1', 'options' => $participants, 'name' => 'Participant[]')) ?>
<?= $this->Form->input('affectsRatings', array('empty' => false, 'id' => 'affectsRatings', 'options' => array(1 => 'Yes', 0 => 'No'), 'label' => 'Does this match affect ratings?')) ?>
<?= $this->Form->end('Save') ?>