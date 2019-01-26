<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-24
 * Time: 14:39
 */
?>

<div class="mdl-layout__content mdl-grid">
    <div class="mdl-card mdl-grid mdl-shadow--2dp mdl-cell mdl-cell--4-col-desktop mdl-cell--4-offset-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Semesterplan als .csv hochladen</h2>
        </div>
        <form action="/prof/check_upload" method="post" enctype="multipart/form-data">
            <div class="mdl-card__supporting-text mdl-grid--no-spacing">
                <div class="mdl-cell--12-col mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                    <input class="mdl-textfield__input" type="text" id="fach_title" name="fach_title">
                    <label class="mdl-textfield__label" for="fach_title">Name des Fachs*</label>
                </div>
                <select class="mdl-cell--6-col mdl-cell--12-col-tablet mdl-cell--12-col-phone klassen_select"
                        name="klassen_select"
                        id="klassen_select">
                    <?php
                    echo '<option value="">Klasse wählen..*</option>';
                    foreach ($klassen as $klasse) {
                        if (!empty($_POST['klassen_select']) && rawurldecode($_POST['klassen_select']) == $klasse->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($klasse->id) . '" selected="selected">' . $klasse->name . '</option>';
                        } else {
                            echo '<option value="' . rawurlencode($klasse->id) . '">' . $klasse->name . '</option>';
                        }
                    }
                    ?>
                </select>
                <select class="mdl-cell--6-col mdl-cell--12-col-tablet mdl-cell--12-col-phone zimmer_select"
                        name="zimmer_select"
                        id="zimmer_select">
                    <?php
                    echo '<option value="">Zimmer wählen..*</option>';
                    foreach ($zimmerList as $zimmer) {
                        foreach ($stockwerkeZimmer as $stockwerkZimmer) {
                            if ($stockwerkZimmer->zimmer_id == $zimmer->id) {
                                foreach ($stockwerke as $stockwerk) {
                                    if ($stockwerkZimmer->stockwerk_id == $stockwerk->id) {
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
                        }
                    }
                    ?>
                </select>
                <h6>Start Zeit*</h6>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                    <input class="mdl-textfield__input" type="time" id="start_time" name="start_time">
                    <label class="mdl-textfield__label" for="start_time">Stunde:Minute:Sekunde*</label>
                </div>
                <h6>End Zeit*</h6>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label userform">
                    <input class="mdl-textfield__input" type="time" id="end_time" name="end_time">
                    <label class="mdl-textfield__label" for="end_time">Stunde:Minute:Sekunde*</label>
                </div>
                <h6>Datei auswählen*</h6>
                <input type="hidden" name="MAX_FILE_SIZE" value="30000">
                <input type="file" name="userfile">
            </div>
            <div class="mdl-card__supporting-text" style="font-style: italic">
                * Mussfelder
            </div>
            <div class="mdl-card__action">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button" id="upload" name="upload">
                    Hochladen
                </button>
            </div>
        </form>
    </div>
</div>