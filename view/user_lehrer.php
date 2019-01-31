<?php
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 27.01.2019
 * Time: 00:23
 */
?>
<div class="mdl-layout__content mdl-grid">
    <div class="mdl-cell mdl-grid--no-spacing mdl-card mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-color-text--white">Lehrerliste</h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-shadow--2dp"
               id="users_table">
            <thead>
            <tr>
                <th class="user_table">Vorname</th>
                <th class="user_table">Nachname</th>
                <th class="user_table">Email</th>
                <th class="user_table">Meine Fächer</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $row) {
                $fachNumber = 0;
                $klasseNumber = 0;
                $meineFaecher = '';
                foreach ($klassen as $klasse) {
                    if ($klasse->klassen_lp == $row->id) {
                        $klasseNumber++;
                    }
                }
                foreach ($faecher as $fach) {
                    if ($fach->lehrer_id == $row->id) {
                        if ($fachNumber == 0) {
                            $meineFaecher = $fach->titel;
                        } else {
                            $meineFaecher .= ', ' . $fach->titel;
                        }
                        $fachNumber++;
                    }
                }
                if ($fachNumber == 0 && $klasseNumber == 0) {
                    continue;
                }
                echo '
          <tr">
          <td>' . $row->vorname . '</td>
          <td>' . $row->nachname . '</td>
          <td>' . $row->email . '</td>
          <td>';
echo $meineFaecher;
                echo '</td>
          </tr>
          ';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
