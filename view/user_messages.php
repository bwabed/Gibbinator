<?php
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 19.01.2019
 * Time: 18:17
 */
?>

<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Nachrichten</h2>
        </div>
        <div class="mdl-card__supporting-text">
        </div>
        <?php
        if ($_SESSION['userType'] ['id'] == 2) { ?>
            <table class="mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-shadow--2dp"
                   id="users_table">
                <thead>
                <tr>
                    <th class="user_table">Bearbeiten</th>
                    <th class="user_table">Titel</th>
                    <th class="user_table">Nachricht</th>
                    <th class="user_table">Erstellt am</th>
                    <th class="user_table">Klasse</th>
                    <th class="user_table">Lektion</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($nachrichten as $row) {
                    echo '
          <tr data-id="' . $row->id . '">
          <td>
          <form id="form-select-message-' . $row->id . '" action="/user/edit_message" method="post">
          <a href="#" id="form-select-message-button-' . $row->id . '" class="mdl-navigation__link">
          <i class="material-icons">edit</i>
          </a>
          <input type="hidden" name="nachrichten_id" value="' . $row->id . '">
          <script>
          $(document).ready(function() {
            $("#form-select-message-button-' . $row->id . '").click(function(e) {
              $("#form-select-message-' . $row->id . '").submit();
            });
          });
          </script>
          </form>
          </td>
          <td>' . $row->titel . '</td>
          <td>' . $row->text . '</td>
          <td>' . $row->erstellt_am . '</td>
          <td>';
                    foreach ($klassen as $klasse) {
                        if ($klasse->id == $row->klassen_id) {
                            echo $klasse->name;
                        }
                    }
                    echo '</td>
          <td>';
                    foreach ($lektionen as $lektion) {
                        if ($lektion->id == $row->lektion_id) {
                            echo $lektion->titel;
                        }
                    }
                    echo '</td>
          </tr>
          ';
                }
                ?>
                </tbody>
            </table>
        <?php } elseif ($_SESSION['userType']['id'] == 3) { ?>
            <ul class="mdl-list">
                <?php
                foreach ($nachrichten as $nachricht) {
                    echo '<li class="mdl-list__item mdl-list__item--three-line">
                            <span class="mdl-list__item-primary-content">
                                <span>';
                                foreach ($teachers as $teacher) {
                                    if ($teacher->id == $nachricht->erfasser_id) {
                                        $creator = $teacher->email;
                                    }
                                }
                                if ($nachricht->klassen_id != null) {
                                    foreach ($klassen as $klasse) {
                                        if ($klasse->id == $nachricht->klassen_id) {
                                            if ($nachricht->lektion_id != null) {
                                                foreach ($lektionen as $lektion) {
                                                    if ($lektion->id == $nachricht->lektion_id) {
                                                        echo 'Von: ' . $creator . ' An: ' . $klasse->name . '/' . $lektion->titel;
                                                    }
                                                }
                                            } else {
                                                echo 'Von: ' . $creator . ' An: ' . $klasse->name;
                                            }
                                        }
                                    }
                                } else {
                                    echo 'Von: ' . $creator;
                                }

                                echo '</span>
                                <span class="mdl-list__item-text-body">' . $nachricht->text . '</span>
                            </span>
                          </li>';
                }
                ?>
            </ul>
        <?php } ?>
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
