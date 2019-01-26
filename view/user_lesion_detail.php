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
        <div class="mdl-card__title mdl-color--grey-500">
            <h6 class="mdl-card__title-text">
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
            if ($_SESSION['userType']['id'] == 2) {
                if ($lesion->programm_themen != null) {
                    echo '<h5>Programm und Themen</h5>';
                    echo $lesion->programm_themen;
                }
                if ($lesion->termine_aufgaben != null) {
                    echo '<h5>Termine und Aufgaben</h5>';
                    echo $lesion->termine_aufgaben;
                }
                ?>
                <h5>Gebäude</h5>
                <?php
                echo $build->bezeichnung . '<br/>' . $build->strasse . ' ' . $build->nr . '<br/>' . $build->plz . ', ' . $build->ort;
                ?>
                <h5>Stockwerk und Zimmer</h5>
                <?php
                echo $stockwerk->bezeichnung . ', ' . $zimmer->bezeichnung;
            }
            if ($_SESSION['userType']['id'] == 3) {
                if ($lesion->programm_themen != null) {
                    echo '<h5>Programm und Themen</h5>';
                    echo $lesion->programm_themen;
                }
                if ($lesion->termine_aufgaben != null) {
                    echo '<h5>Termine und Aufgaben</h5>';
                    echo $lesion->termine_aufgaben;
                }
                ?>
                <h5>Gebäude</h5>
                <?php
                echo $build->bezeichnung . '<br/>' . $build->strasse . ' ' . $build->nr . '<br/>' . $build->plz . ', ' . $build->ort;
                ?>
                <h5>Stockwerk und Zimmer</h5>
                <?php
                echo $stockwerk->bezeichnung . ', ' . $zimmer->bezeichnung;
            }
            ?>
        </div>
    </div>
    <div class="mdl-card mdl-cell--2-offset-desktop mdl-cell mdl-cell--8-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--grey-500">
            <h6 class="mdl-card__title-text">Nachrichten</h6>
        </div>
        <div class="mdl-card__supporting-text">
        </div>
        <ul class="mdl-list">
            <?php
            foreach ($nachrichten as $nachricht) {?>
               <li class="mdl-list__item">
                   <span><?= $nachricht->titel ?></span>
               </li>
            <?php
            }
            ?>
        </ul>
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