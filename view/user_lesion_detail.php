<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-25
 * Time: 12:45
 */
$dateString = strtotime($date->start_date);
$startTime = strtotime($date->start_time);
$endTime = strtotime($date->end_time);
?>
<div class="mdl-layout__content mdl-grid">
    <div class="mdl-card mdl-cell mdl-cell--8-col-desktop mdl-cell--2-offset-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h6 class="mdl-card__title-text mdl-color-text--white">
                Lektion <?= $fach->titel . ', ' . date('d.m.Y', $dateString) . ', ' . date('H:i', $startTime) . ' - ' . date('H:i', $endTime) ?>
                <?php
                if ($_SESSION['userType']['id'] == 3) {
                    echo '
                <br/>Lehrperson: ' . $user->email . '</h6>';
                }
                ?>
        </div>
        <div class="mdl-card__supporting-text">
            <?php
                if ($lesion->programm_themen != null) {
                    echo '<h5>Programm und Themen</h5>';
                    echo nl2br($lesion->programm_themen);
                }
                if ($lesion->termine_aufgaben != null) {
                    echo '<h5>Termine und Aufgaben</h5>';
                    echo nl2br($lesion->termine_aufgaben);
                }
                ?>
                <h5>Gebäude</h5>
                <?php
                echo $build->bezeichnung . '<br/>' . $build->strasse . ' ' . $build->nr . '<br/>' . $build->plz . ', ' . $build->ort;
                ?>
                <h5>Stockwerk und Zimmer</h5>
                <?php
                echo $stockwerk->bezeichnung . ', ' . $zimmer->bezeichnung;
            ?>
        </div>
    </div>
    <div class="mdl-card mdl-cell--2-offset-desktop mdl-cell mdl-cell--8-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h6 class="mdl-card__title-text mdl-color-text--white">Nachrichten</h6>
        </div>
        <div class="mdl-card__supporting-text">
        </div>
        <div id="messages">
            <?php
            foreach ($nachrichten as $nachricht) {
                echo '<div style="border: grey; border-style: solid; border-radius: 5px; border-width: thin"><div class="mdl-card__title mdl-color--indigo-400">
                            <h6 class="mdl-card__title-text mdl-color-text--white" style="font-size: 10pt">';
                if (!empty($klasse->name) && !empty($fach->titel)) {
                    echo 'Von: ' . $user->email . ' -> ' . $klasse->name . '/' . $fach->titel;
                } elseif (!empty($klasse->name) && empty($fach->titel)) {
                    echo 'Von: ' . $user->email . ' -> ' . $klasse->name;
                } elseif (empty($klasse->name) && !empty($fach->titel)) {
                    echo 'Von: ' . $user->email . ' -> ' . $fach->titel;
                }
                echo '</h6>
</div><div class="mdl-card__supporting-text">';
                echo nl2br($nachricht->text);
                echo '</div><div class="mdl-card__supporting-text" style="font-style: italic">
                    ' . date('d.m.Y', strtotime($nachricht->erstellt_am)) . '
                </div></div>';

            }
            ?>
        </div>
        <?php if ($_SESSION['userType']['id'] == 2) { ?>
            <form action="/user/new_message" method="post">
                <div class="mdl-card__actions mdl-card--border">
                    <input type="hidden" id="lektion_id" name="lektion_id" class="lektion_id"
                           value="<?= $lesion->id ?>">
                    <button class="mdl-button mdl-button--colored new_message_button mdl-js-ripple-effect mdl-js-button mdl-button--raised" id="new_message_button">Neue
                        Nachricht
                    </button>
                </div>
            </form>
        <?php } ?>
    </div>
</div>