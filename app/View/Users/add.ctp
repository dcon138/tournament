<?= $this->Form->create('User') ?>
<?= $this->Form->input('firstname') ?>
<?= $this->Form->input('lastname') ?>
<?= $this->Form->input('displayName') ?>
<?= $this->Form->input('email') ?>
<?= $this->Form->input('password') ?>
<?= $this->Form->input('confirmPassword', array('type' => 'password')) ?>
<?= $this->Form->input('homePhone') ?>
<?= $this->Form->input('mobile') ?>
<?= $this->Form->input('workPhone') ?>
<?= $this->Form->end('Save') ?>