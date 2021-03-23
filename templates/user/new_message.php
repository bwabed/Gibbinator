<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-21
 * Time: 17:53
 */
?>

<div class="mdl-grid mdl-layout">
    <div class="mdl-card mdl-cell mdl-cell--4-col-desktop mdl-cell--4-offset-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Neue Nachricht</h2>
        </div>
        <form action="/user/create_message" method="post">
            <div class="mdl-card__supporting-text mdl-grid">
                <div class="mdl-cell--12-col mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="new_title" name="new_title"
                           value="<?= isset($_POST['new_title']) ? htmlspecialchars(strip_tags($_POST['new_title'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="new_title">Titel*</label>
                </div>
                <div class="mdl-cell--12-col mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" rows="5" type="text" id="new_message_text" name="new_message_text"
                           value="<?= isset($_POST['new_message_text']) ? htmlspecialchars(strip_tags($_POST['new_message_text'])) : ''; ?>"></textarea>
                    <label class="mdl-textfield__label" for="new_message_text">Nachricht*</label>
                </div>
                <?php if ($lektion == null) { ?>
                    <select class="mdl-cell--6-col mdl-cell--4-col-tablet mdl-cell--2-col-phone klassen_select"
                            name="klassen_select"
                            id="klassen_select">
                        <?php
                        echo '<option value="">Klasse wählen..</option>';
                        foreach ($klassen as $klasse) {
                            if (!empty($_POST['klassen_select']) && rawurldecode($_POST['klassen_select']) == $klasse->id) {
                                echo '<option class="mdl-menu__item" value="' . rawurlencode($klasse->id) . '" selected="selected">' . $klasse->name . '</option>';
                            } else {
                                echo '<option value="' . rawurlencode($klasse->id) . '">' . $klasse->name . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <select class="mdl-cell--6-col mdl-cell--4-col-tablet mdl-cell--2-col-phone fach_select"
                            name="fach_select"
                            id="fach_select">
                        <?php
                        echo '<option value="">Fach wählen..</option>';
                        foreach ($faecher as $fach) {
                            if (!empty($_POST['fach_select']) && rawurldecode($_POST['fach_select']) == $fach->id) {
                                echo '<option class="mdl-menu__item" value="' . rawurlencode($fach->id) . '" selected="selected">' . $fach->titel . '</option>';
                            } else {
                                echo '<option value="' . rawurlencode($fach->id) . '">' . $fach->titel . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <?php
                }
                if ($lektion != null) {
                    echo '<input type="hidden" class="lektion_id" id="lektion_id" name="lektion_id" value="' . $lektion->id . '">';
                    ?>
                    <br/>
                        <?php
                        $dateString = strtotime($date->start_date);
                        foreach ($faecher as $fach) {
                            if ($lektion->fach_id == $fach->id) {
                                echo $fach->titel . ', ' . date('d.m.Y', $dateString);
                            }
                        }
                }
                ?>
                <div class="mdl-card__supporting-text" style="font-style: italic">
                    * Mussfelder
                </div>
            </div>
            <div class="mdl-card__actions">
                <button id="new_message" class="mdl-button mdl-button--colored mdl-card--border">Erstellen</button>
            </div>
        </form>
    </div>
</div>