<?php
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 19.01.2019
 * Time: 19:04
 */
?>
<div class="mdl-layout__content mdl-grid">
    <div class="mdl-card mdl-grid mdl-grid--no-spacing mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">
                Lernende hinzufügen
            </h2>
        </div>
        <table class="mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
               id="users_table">
            <thead>
            <tr>
                <th class="users_table">Vorname</th>
                <th class="users_table">Nachname</th>
                <th class="users_table">Email</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($lernende as $row) {
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
            <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button mdl-color--red"
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

                        $('table#users_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedUsers.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedUsers.length != 0) {
                            $.post("/admin/edit_klasse", {add_users: selectedUsers, klassen_id: <?= $klassen_id ?>})
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
                    });
                });
            </script>
        </div>
    </div>
</div>
