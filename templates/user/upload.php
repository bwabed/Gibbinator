<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-24
 * Time: 14:39
 */
?>
<div class="mdl-layout__content mdl-grid">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col-desktop mdl-cell--3-offset-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Semesterplan als .csv hochladen</h2>
        </div>
        <form action="/user/check_upload" method="post" enctype="multipart/form-data">
            <div class="mdl-card__supporting-text mdl-grid--no-spacing">
                <select class="mdl-cell--12-col fach_select"
                        name="fach_select"
                        id="fach_select">
                    <?php
                    echo '<option value="">Fach wählen..*</option>';
                    foreach ($faecher as $fach) {
                        if (!empty($_POST['fach_select']) && rawurldecode($_POST['fach_select']) == $fach->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($fach->id) . '" selected="selected">' . $fach->titel . '</option>';
                        } else {
                            echo '<option value="' . rawurlencode($fach->id) . '">' . $fach->titel . '</option>';
                        }
                    }
                    ?>
                </select>
                <a href="/user/klassen" target="_self" style="font-style: normal; text-decoration-color: black; color: black; margin-bottom: 5px; margin-top: 5px">Neues Fach erstellen</a>
                <select class="mdl-cell--12-col zimmer_select"
                        name="zimmer_select"
                        id="zimmer_select">
                    <?php
                    echo '<option value="">Zimmer wählen..*</option>';
                    foreach ($zimmerList as $zimmer) {
                        foreach ($stockwerke as $stockwerk) {
                            if ($zimmer->stockwerk_id == $stockwerk->id) {
                                foreach ($buildings as $building) {
                                    if ($building->id == $stockwerk->gebaeude_id) {
                                        if (!empty($_POST['zimmer_select']) && rawurldecode($_POST['zimmer_select']) == $zimmer->id) {
                                            echo '<option class="mdl-menu__item" value="' . rawurlencode($zimmer->id) . '" selected="selected">' . $building->bezeichnung . '/' . $stockwerk->bezeichnung . '/' . $zimmer->bezeichnung . '</option>';
                                        } else {
                                            echo '<option value="' . rawurlencode($zimmer->id) . '">' . $building->bezeichnung . '/' . $stockwerk->bezeichnung . '/' . $zimmer->bezeichnung . '</option>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </select>
                <h6>Start Zeit*</h6>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                    <input class="mdl-textfield__input" type="text" id="start_time" name="start_time">
                    <label class="mdl-textfield__label" for="start_time">00:00*</label>
                </div>
                <h6>End Zeit*</h6>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                    <input class="mdl-textfield__input" type="text" id="end_time" name="end_time">
                    <label class="mdl-textfield__label" for="end_time">00:00*</label>
                </div>
                <h6>Datei auswählen*</h6>
                <input type="hidden" name="MAX_FILE_SIZE" value="30000">
                <input type="file" name="userfile" id="userfile">
            </div>
            <div class="mdl-card__supporting-text" style="font-style: italic">
                * Mussfelder
            </div>
            <div class="mdl-card__action mdl-card--border mdl-cell">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button"
                        id="upload" name="upload">
                    Hochladen
                </button>
            </div>
        </form>
    </div>
</div>