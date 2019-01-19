<?php
/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-18
 * Time: 15:46
 */
?>

<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--4-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--4-offset-desktop mdl-card-form mdl-shadow--2dp">
        <div class="mdl-card__title">
            <h1 class="mdl-card__title-text">Neue Klasse</h1>
        </div>
        <div class="mdl-card__supporting-text">
            <form action="/admin/create_klasse" method="post">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="new_klassenname" name="new_klassenname"
                           value="<?= isset($_POST['new_klassenname']) ? htmlspecialchars(strip_tags($_POST['new_klassenname'])) : ''; ?>">
                    <label class="mdl-textfield__label" for="new_klassenname">Klassenname*</label>
                </div>
                <select class="mdl-cell--6-col mdl-cell--4-col-tablet mdl-cell--2-col-phone klassen_lp_select"
                        name="klassen_lp_select"
                        id="klassen_lp_select">
                    <?php
                    echo '<option value="">Klassenlehrperson wählen..*</option>';
                    foreach ($lehrer as $row) {
                        if (!empty($_POST['klassen_lp_select']) && rawurldecode($_POST['klassen_lp_select']) == $row->id) {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '" selected="selected">' . $row->vorname . ' ' . $row->nachname . '</option>';
                        } else {
                            echo '<option class="mdl-menu__item" value="' . rawurlencode($row->id) . '">' . $row->bezeichnung . ' ' . $row->nachname . '</option>';
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
                Klasse erstellen
            </button>
        </div>
        </form>
    </div>
</div>
