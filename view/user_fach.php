<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-02-01
 * Time: 10:39
 */
?>
<div class="mdl-layout__content mdl-grid">
    <div class="mdl-card mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-phone mdl-cell--8-col-desktop mdl-cell--4-offset-desktop mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Fach bearbeiten</h2>
        </div>
        <form action="/user/update_fach" method="post">
            <div class="mdl-card__supporting-text">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="edit_fachtitle" name="edit_fachtitle"
                           value="<?= isset($_POST['edit_fachtitle']) ? htmlspecialchars(strip_tags($_POST['edit_fachtitle'])) : $fach->titel; ?>">
                    <label class="mdl-textfield__label" for="edit_fachtitle">Titel*</label>
                </div>
                <select class="mdl-cell--12-col edit_fach_lp_select"
                        name="edit_fach_lp_select"
                        id="edit_fach_lp_select">
                    <?php
                    echo '<option value="">Lehrperson wählen..*</option>';
                    foreach ($lehrer as $row) {
                        if (!empty($_POST['edit_fach_lp_select']) && rawurldecode($_POST['edit_fach_lp_select']) == $row->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        } else {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        }
                    }
                    ?>
                </select>
                <select class="mdl-cell--12-col klassen_select"
                        name="klassen_select"
                        id="klassen_select">
                    <?php
                    echo '<option value="">Klasse wählen..*</option>';
                    foreach ($klassen as $row) {
                        if ((!empty($_POST['klassen_select']) && rawurldecode($_POST['klassen_select']) == $row->id) or $fach->klassen_id == $row->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->name . '</option>';
                        } else {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '">' . $row->name . '</option>';
                        }
                    }
                    ?>
                </select>
                <input type="hidden" id="fach_id" name="fach_id" value="<?= $fach->id ?>">
                <div class="mdl-card__supporting-text" style="font-style: italic">
                    * Mussfelder
                </div>
            </div>
            <div class="mdl-card__actions mdl-card--border send-button">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="create">
                    Fach erstellen
                </button>
            </div>
        </form>
    </div>
</div>
