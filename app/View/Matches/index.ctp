<?= $this->Html->script('matches') ?>

<a class="btn btn-primary pull-right" href="<?= Router::url(array('action' => 'add')) ?>">Add Match</a>
<?php if (empty($matches)): ?>
    <p>There are no matches that match your current filters.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Date Played</th>
                <th>Players</th>
                <th>Winners</th>
                <th>Scores</th>
                <th>Scoring System</th>
                <!-- if its an admin -->
                    <th>Actions</th>
                <!-- endif -->
            </tr>
        </thead>
        <tbody>
    <?php foreach ($matches as $match): ?>
        <tr id="row-<?= $match['Match']['id'] ?>">
            <td><?= $match['Match']['datePlayed'] ?></td>
            <td>
                <?php
                    $winners = array();
                    if (!empty($match['Participant'])) {
                        foreach ($match['Participant'] as $player) {
                            echo $player['firstname'] . ' ' . $player['lastname'] . "<br/>";
                            if ($player['MatchPlayer']['winner']) {
                                $winners[] = $player['firstname'] . ' ' . $player['lastname'];
                            }
                        }
                    }
                ?>
            </td>
            <td class="winners_col">
                <?php
                    if (!empty($winners)) {
                        echo implode('<br/>', $winners);
                    }
                ?>
            </td>
            <td>
                <?php
                    if (!empty($match['Participant'])) {
                        foreach ($match['Participant'] as $player) {
                            echo $player['MatchPlayer']['score'] . "<br/>";
                        }
                    }
                ?>
            </td>
            <td><?= $match['ScoringSystem']['name'] ?></td>
            <!-- if its an admin -->
            <td><a class="calculate_winners" id="calculate_winners_<?= $match['Match']['id'] ?>">Calculate Winners</a></td>
            <!-- endif -->
        </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>