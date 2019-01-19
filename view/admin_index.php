<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-card-form mdl-cell--12-col mdl-shadow--2dp" id="user_results">
        <div class="mdl-card__title">
            <h3>Benutzer</h3>
        </div>
        <table class="mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"
               id="users_table">
            <thead>
            <tr>
                <th class="user_table">Bearbeiten</th>
                <th class="user_table">Vorname</th>
                <th class="user_table">Nachname</th>
                <th class="user_table">Email</th>
                <th class="user_table">Benutzer Typ</th>
                <th class="user_table">Initial Passwort</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $row) {
                echo '
          <tr data-id="' . $row->id . '">
          <td>
          <form id="form-select-user-' . $row->id . '" action="/admin/edit_user" method="post">
          <a href="#" id="form-select-user-button-' . $row->id . '" class="mdl-navigation__link">
          <i class="material-icons">edit</i>
          </a>
          <input type="hidden" name="user_id" value="' . $row->id . '">
          <script>
          $(document).ready(function() {
            $("#form-select-user-button-' . $row->id . '").click(function(e) {
              $("#form-select-user-' . $row->id . '").submit();
            });
          });
          </script>
          </form>
          </td>
          <td>' . $row->vorname . '</td>
          <td>' . $row->nachname . '</td>
          <td>' . $row->email . '</td>
          <td>';
                foreach ($usertypes as $usertype) {
                    if ($usertype->id == $row->user_type) {
                        echo $usertype->bezeichnung;
                    }
                }
                echo '</td>
          <td>';
                if ($row->initial_pw == 0) {
                    echo 'Nein';
                } else {
                    echo 'Ja';
                }
                echo '</td>
          </tr>
          ';
            }
            ?>
            </tbody>
        </table>
        <div class="mdl-card__actions mdl-card--border">
            <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button mdl-color--red"
                    id="delete_button">
                Benutzer Löschen
            </button>
            <a class="addUserButton mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored form_button add_to_button"
               id="add_button" href="/admin/new_user">
                Neuer Benutzer
            </a>

            <script type="text/javascript">
                // Diese Funktion wird erst ausgeführt, sobald auf denn "add to cart" button geklickt wurde.
                // Sie schaut nach, welche Karten ausgewehlt wurden und speichert deren ID (weiter oben mit PHP verteilt) in einem Array.
                // Falls dieser Array nicht leer ist schickt sie den Array an die Funktion add_cards_to_cart im UserController. Sonst gibt sie eine Fehlermeldung zurück.
                $(document).ready(function () {
                    $('#delete_button').click(function (e) {

                        var selectedUsers = [];

                        $('table#users_table tbody tr td:first-child input').each(function (index, value) {
                            if (value.checked) {
                                selectedUsers.push($(value).parent().parent().parent().data('id'));
                            }
                        });

                        if (selectedUsers.length != 0) {
                            $.post("/admin/delete_selected_user", {users: selectedUsers})
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
</div>