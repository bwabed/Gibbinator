<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--6-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--3-offset-desktop mdl-card-form mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h1 class="mdl-card__title-text mdl-color-text--white">Benutzer bearbeiten</h1>
        </div>
        <div class="mdl-card__supporting-text">
            <form action="/admin/check_edit_user" method="post">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="email" id="edit_username" name="edit_username"
                           value="<?= isset($_POST['edit_username']) ? htmlspecialchars(strip_tags($_POST['edit_username'])) : $userData->email; ?>">
                    <label class="mdl-textfield__label" for="edit_username">Email*</label>
                </div>
                <input type="hidden" id="edit_user_id" name="edit_user_id" value="<?= $userData->id?>">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="password" id="edit_password" name="edit_password"
                           value="<?= isset($_POST['edit_password']) ? htmlspecialchars(strip_tags($_POST['edit_password'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="edit_password">Passwort*</label>
                </div>
                <label for="edit_pw_checkbox" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                    <?php if ($userData->initial_pw == 0) { ?>
                        <input type="checkbox" id="edit_pw_checkbox" class="mdl-checkbox__input">
                    <?php } else { ?>
                        <input type="checkbox" id="edit_pw_checkbox" class="mdl-checkbox__input" checked>
                    <?php } ?>
                    <span class="mdl-checkbox__label">Initial Passwort</span>
                </label>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="edit_vorname" name="edit_vorname"
                           value="<?= isset($_POST['edit_vorname']) ? htmlspecialchars(strip_tags($_POST['edit_vorname'])) : $userData->vorname; ?>">
                    <label class="mdl-textfield__label" for="edit_vorname">Vorname*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="edit_nachname" name="edit_nachname"
                           value="<?= isset($_POST['edit_nachname']) ? htmlspecialchars(strip_tags($_POST['edit_nachname'])) : $userData->nachname; ?>">
                    <label class="mdl-textfield__label" for="edit_nachname">Nachname*</label>
                </div>
                <select class="mdl-cell--6-col mdl-cell--4-col-tablet mdl-cell--2-col-phone edit_usertype_select"
                        name="edit_usertype_select"
                        id="edit_usertype_select">
                    <?php
                    echo '<option value="">Benutzertyp wählen..*</option>';
                    foreach ($usertypes as $row) {
                        if ((!empty($_POST['edit_usertype_select']) && rawurldecode($_POST['edit_usertype_select']) == $row->id) or $userData->user_type == $row->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->bezeichnung . '</option>';
                        } else {
                            echo '<option value="' . rawurlencode($row->id) . '">' . $row->bezeichnung . '</option>';
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
                Änderungen Speichern
            </button>
        </div>
        </form>
    </div>
</div>