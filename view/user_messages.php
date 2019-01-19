<?php
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 19.01.2019
 * Time: 18:17
 */
?>

<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
        <div class="mdl-layout-title">
            <h2 class="mdl-card__title-text">Nachrichten</h2>
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
            foreach ($nachrichten as $row) {
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
        <div class="mdl-card__supporting-text">

        </div>
        <div class="mdl-card__actions">
            <?php
            if ($_SESSION['userType']['id'] == 2) {
                echo '
                <form action="/user/new_message" method="post">
                    <input type="hidden" id="user_id" name="user_id" value="' . $_SESSION['user']['id'] . '">
                    <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                            id="add_button" href="/admin/new_message">
                        Neue Nachricht
                    </button>
                </form>';
            }
            ?>

        </div>
    </div>
</div>
