<?php
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 27.01.2019
 * Time: 16:19
 */
?>
<div class="mdl-layout__content mdl-grid">
    <?php
    foreach ($klassen as $klasse) {
        foreach ($profs as $prof) {
            if ($prof->id == $klasse->klassen_lp) {
                $klassenLp = $prof->vorname . ' ' . $prof->nachname;
            }
        }
        ?>
        <div class="mdl-card mdl-grid--no-spacing mdl-cell mdl-cell--8-col-desktop mdl-cell--2-offset-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone mdl-shadow--2dp">
            <div class="mdl-card__title mdl-color--indigo-500">
                <h2 class="mdl-color-text--white"><?= $klasse->name ?></h2>
            </div>
            <div class="mdl-card__supporting-text">
                <h4>Klassenlehrperson: <?= $klassenLp ?></h4>
                <h3>Mitglieder</h3>
            </div>
            <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-shadow--2dp"
                   id="users_in_table">
                <thead>
                <tr>
                    <th class="users_in_table">Vorname</th>
                    <th class="users_in_table">Nachname</th>
                    <th class="users_in_table">Email</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($klassenUser as $klassen_user) {
                    if ($klassen_user->klassen_id == $klasse->id) {
                        foreach ($users as $user) {
                            if ($user->id == $klassen_user->user_id) {
                                echo '
                                  <tr data-id="' . $user->id . '">
                                  <td>' . $user->vorname . '</td>
                                  <td>' . $user->nachname . '</td>
                                  <td>' . $user->email . '</td>
                                  </tr>
                                  ';
                            }
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    ?>
</div>