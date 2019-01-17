<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--6-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--3-offset-desktop mdl-card-form mdl-shadow--2dp">
        <div class="mdl-card__title">
            <h1 class="mdl-card__title-text">Neuer Benutzer</h1>
        </div>
        <div class="mdl-card__supporting-text">
            <form action="/admin/create_user" method="post">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="email" id="new_username" name="new_username" value="<?= isset($_POST['new_username']) ? htmlspecialchars(strip_tags($_POST['new_username'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="new_username">Email*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="password" id="new_password" name="new_password" value="<?= isset($_POST['new_password']) ? htmlspecialchars(strip_tags($_POST['new_password'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="new_password">Passwort*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="new_vorname" name="new_vorname" value="<?= isset($_POST['new_vorname']) ? htmlspecialchars(strip_tags($_POST['new_vorname'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="new_vorname">Vorname*</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="new_nachname" name="new_nachname" value="<?= isset($_POST['new_nachname']) ? htmlspecialchars(strip_tags($_POST['new_nachname'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="new_nachname">Nachname*</label>
                </div>
                <label class="mdl-checkbox mdl-checkbox__ripple-container" for="pw_checkbox">
                    <input value="1" type="checkbox" id="pw_checkbox" class="mdl-checkbox__input" checked>
                    <span class="mdl-checkbox__label">Initialpasswort</span>
                </label>
                <select class="mdl-cell--6-col mdl-cell--4-col-tablet mdl-cell--2-col-phone usertype_select" name="usertype_select"
                        id="usertype_select">
                    <?php
                    echo '<option value="">Benutzertyp wählen..*</option>';
                    foreach ($usertypes as $row) {
                        if (!empty($_POST['usertype_select']) && rawurldecode($_POST['usertype_select']) == $row->id) {
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
                Benutzer erstellen
            </button>
        </div>
        </form>
    </div>
</div>