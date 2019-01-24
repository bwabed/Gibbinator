<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-21
 * Time: 17:17
 */
?>

<div class="mdl-grid mdl-layout">
    <div class="mdl-card mdl-cell mdl-cell--4-col-desktop mdl-cell--4-offset-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Nachricht bearbeiten</h2>
        </div>
        <form action="/user/update_message" method="post">
            <div class="mdl-card__supporting-text">
                <input type="hidden" name="edit_nachricht_id" id="edit_nachricht_id" value="<?= $nachricht->id ?>">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="new_title" name="edit_title"
                           value="<?= isset($_POST['edit_title']) ? htmlspecialchars(strip_tags($_POST['edit_title'])) : $nachricht->titel; ?>">
                    <label class="mdl-textfield__label" for="edit_title">Titel*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="edit_message_text" name="edit_message_text"
                           value="<?= isset($_POST['edit_message_text']) ? htmlspecialchars(strip_tags($_POST['edit_message_text'])) : $nachricht->text; ?>">
                    <label class="mdl-textfield__label" for="edit_message_text">Nachricht*</label>
                </div>
                <select class="mdl-cell--6-col mdl-cell--4-col-tablet mdl-cell--2-col-phone edit_klassen_select"
                        name="edit_klassen_select"
                        id="edit_klassen_select">
                    <?php
                    echo '<option value="">Klasse wählen..</option>';
                    foreach ($klassen as $klasse) {
                        if ((!empty($_POST['edit_klassen_select']) && rawurldecode($_POST['edit_klassen_select']) == $klasse->id) or $nachricht->klassen_id == $klasse->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($klasse->id) . '" selected="selected">' . $klasse->name . '</option>';
                        } else {
                            echo '<option value="' . rawurlencode($klasse->id) . '">' . $klasse->name . '</option>';
                        }
                    }
                    ?>
                </select>
                <select class="mdl-cell--6-col mdl-cell--4-col-tablet mdl-cell--2-col-phone edit_lektion_select"
                        name="edit_lektion_select"
                        id="edit_lektion_select">
                    <?php
                    echo '<option value="">Lektion wählen..</option>';
                    foreach ($lektionen as $lektion) {
                        if ((!empty($_POST['edit_lektion_select']) && rawurldecode($_POST['edit_lektion_select']) == $lektion->id) or $nachricht->lektion_id == $lektion->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($lektion->id) . '" selected="selected">' . $lektion->titel . '</option>';
                        } else {
                            echo '<option value="' . rawurlencode($lektion->id) . '">' . $lektion->titel . '</option>';
                        }
                    }
                    ?>
                </select>
                <div class="mdl-card__supporting-text" style="font-style: italic">
                    * Mussfelder
                </div>
            </div>
            <div class="mdl-card__actions">
                <button id="edit_message" class="mdl-button mdl-button--colored mdl-card--border">Speichern</button>
            </div>
        </form>
    </div>
</div>
