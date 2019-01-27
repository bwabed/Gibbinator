<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-18
 * Time: 16:30
 */
?>

<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-card-form mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h1 class="mdl-card__title-text mdl-color-text--white">Klasse bearbeiten</h1>
        </div>
        <div class="mdl-card__supporting-text">
            <form action="/admin/update_klasse" method="post">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="edit_klassenname" name="edit_klassenname"
                           value="<?= isset($_POST['edit_klassenname']) ? htmlspecialchars(strip_tags($_POST['edit_klassenname'])) : $klasse->name; ?>">
                    <label class="mdl-textfield__label" for="edit_klassenname">Klassenname*</label>
                </div>
                <input type="hidden" id="edit_klassen_id" name="edit_klassen_id" value="<?= $klasse->id ?>">
                <select class="mdl-cell--6-col mdl-cell--4-col-tablet mdl-cell--2-col-phone edit_klassen_lp_select"
                        name="edit_klassen_lp_select"
                        id="edit_klassen_lp_select">
                    <?php
                    echo '<option value="">Klassen Lehrperson wählen..*</option>';
                    foreach ($lehrer as $row) {
                        if ((!empty($_POST['edit_klassen_lp_select']) && rawurldecode($_POST['edit_klassen_lp_select']) == $row->id) or $klassen_lp->id == $row->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        } else {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        }
                    }
                    ?>
                </select>
                <div class="mdl-card__supporting-text" style="font-style: italic">
                    * Mussfelder
                </div>
        </div>
        <div class="mdl-card__actions mdl-card--border send-button">
            <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                    id="create">
                Speichern
            </button>
        </div>
        </form>
    </div>
    <div class="mdl-card mdl-grid mdl-grid--no-spacing mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-cell mdl-cell--12-col mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">Lernende in Klasse</h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
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
            foreach ($lernende_in as $row) {
                echo '
                      <tr data-id="' . $row->id . '">
                      <td>' . $row->vorname . '</td>
                      <td>' . $row->nachname . '</td>
                      <td>' . $row->email . '</td>
                      </tr>
                      ';
            }
            ?>
            </tbody>
        </table>
        <div class="mdl-card__actions mdl-card--border">
            <button class="mdl-button mdl-cell mdl-cell--3-col mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button mdl-color--red"
                    id="delete_button">
                Auswahl entfernen
            </button>
            <script type="text/javascript">
                // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                $(document).ready(function () {
                    $('#delete_button').click(function (e) {
                        e.preventDefault();

                        var selectedUsers = [];

                        $('table#users_in_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedUsers.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedUsers.length != 0) {
                            $.post("/admin/edit_klasse", {delete_users: selectedUsers, klassen_id: <?= $klasse->id ?>})
                                .done(function (data) {
                                    'use strict';
                                    var snackbarContainer = document.querySelector('#snackbar');
                                    var data = {message: 'Benutzer erfolgreich gelöscht.'};
                                    snackbarContainer.MaterialSnackbar.showSnackbar(data);
                                });
                        } else {
                            var snackbarContainer = document.querySelector('#snackbar');
                            var data = {message: 'Bitte mindestens ein Benutzer wählen!'};
                            snackbarContainer.MaterialSnackbar.showSnackbar(data);
                        }
                        window.location.reload();
                    });
                });
            </script>
        </div>
    </div>
    <div class="mdl-card mdl-grid mdl-grid--no-spacing mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-cell mdl-cell--12-col mdl-card__title mdl-color--indigo-500">
            <h2 class="mdl-card__title-text mdl-color-text--white">
                Lernende hinzufügen
            </h2>
        </div>
        <table class="customTable mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
               id="users_out_table">
            <thead>
            <tr>
                <th class="users_out_table">Vorname</th>
                <th class="users_out_table">Nachname</th>
                <th class="users_out_table">Email</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($lernende_out as $row) {
                echo '
                      <tr data-id="' . $row->id . '">
                      <td>' . $row->vorname . '</td>
                      <td>' . $row->nachname . '</td>
                      <td>' . $row->email . '</td>
                      </tr>
                      ';
            }
            ?>
            </tbody>
        </table>
        <div class="mdl-card__actions mdl-card--border">
            <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button"
                    id="add_button">
                Hinzufügen
            </button>
            <script type="text/javascript">
                // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                $(document).ready(function () {
                    $('#add_button').click(function (e) {

                        var selectedUsers = [];

                        $('table#users_out_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedUsers.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedUsers.length != 0) {
                            $.post("/admin/edit_klasse", {add_users: selectedUsers, klassen_id: <?= $klasse->id ?>})
                                .done(function (data) {
                                    'use strict';
                                    var snackbarContainer = document.querySelector('#snackbar');
                                    var data = {message: 'Benutzer erfolgreich hinzugefügt.'};
                                    snackbarContainer.MaterialSnackbar.showSnackbar(data);
                                });
                        } else {
                            var snackbarContainer = document.querySelector('#snackbar');
                            var data = {message: 'Bitte mindestens ein Benutzer wählen!'};
                            snackbarContainer.MaterialSnackbar.showSnackbar(data);
                        }
                        window.location.reload();
                    });
                });
            </script>
        </div>
    </div>
</div>
