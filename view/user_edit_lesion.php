<div class="mdl-layout__content mdl-grid">
    <div class="mdl-card mdl-cell mdl-cell--8-col-desktop mdl-cell--2-offset-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <?php
            $date = strtotime($date->start_date);
            ?>
            <h2 class="mdl-card__title-text mdl-color-text--white">Lektion vom <?= date('d.m.Y', $date) ?> bearbeiten</h2>
        </div>
        <form method="post" action="/user/update_lesion">
            <div class="mdl-card__supporting-text">
                <h5>Programm und Themen</h5>
                <div class="mdl-cell--12-col mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" rows="5" type="text" id="edit_prog_them"
                              name="edit_prog_them"
                              ><?php if (isset($lesion->programm_themen) && $lesion->programm_themen != null) {
                            echo $lesion->programm_themen;
                        } elseif (isset($_POST['edit_prog_them'])) {
                            echo htmlspecialchars($_POST['edit_prog_them']);
                        } ?></textarea>
                    <label class="mdl-textfield__label" for="edit_prog_them">Text</label>
                </div>
                <h5>Termine und Aufgaben</h5>
                <div class="mdl-cell--12-col mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" rows="5" type="text" id="edit_term_aufg"
                              name="edit_term_aufg"
                              ><?php if (isset($lesion->termine_aufgaben) && $lesion->termine_aufgaben != null) {
                            echo $lesion->termine_aufgaben;
                        } elseif (isset($_POST['edit_term_aufg'])) {
                            echo htmlspecialchars($_POST['edit_term_aufg']);
                        } ?></textarea>
                    <label class="mdl-textfield__label" for="edit_term_aufg">Text</label>
                </div>
                <h5>Gebäude</h5>
                <?php
                echo $build->bezeichnung . '<br/>' . $build->strasse . ' ' . $build->nr . '<br/>' . $build->plz . ', ' . $build->ort;
                ?>
                <h5>Stockwerk und Zimmer</h5>
                <?php
                echo $stockwerk->bezeichnung . ', ' . $zimmer->bezeichnung;
                ?>
            </div>
            <input type="hidden" id="lektion_id" name="lektion_id" value="<?= $lesion->id ?>">
            <div class="mdl-card__actions mdl-card--border">
                <button class="mdl-button mdl-button--colored sppeichern_button mdl-js-ripple-effect mdl-js-button mdl-button--raised"
                        id="speichern_button" value="speichern_button" name="speichern_button">Speichern
                </button>
                <button class="mdl-button mdl-button--colored delete_button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-color--red"
                        id="delete_button" value="delete_button" name="delete_button">Löschen
                </button>
            </div>
        </form>
    </div>
    <div class="mdl-card mdl-cell--2-offset-desktop mdl-cell mdl-cell--8-col-desktop mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-shadow--2dp">
        <div class="mdl-card__title mdl-color--indigo-500">
            <h6 class="mdl-card__title-text mdl-color-text--white">Nachrichten</h6>
        </div>
        <div class="mdl-card__supporting-text">
        </div>
        <div id="messages">
            <?php
            foreach ($nachrichten as $nachricht) {
                echo '<div style="border: grey; border-style: solid; border-radius: 5px; border-width: thin"><div class="mdl-card__title mdl-color--indigo-400">
                            <h6 class="mdl-card__title-text mdl-color-text--white" style="font-size: 10pt">';
                if (!empty($klasse->name) && !empty($fach->titel)) {
                    echo 'Von: ' . $user->email . ' -> ' . $klasse->name . '/' . $fach->titel;
                } elseif (!empty($klasse->name) && empty($fach->titel)) {
                    echo 'Von: ' . $user->email . ' -> ' . $klasse->name;
                } elseif (empty($klasse->name) && !empty($fach->titel)) {
                    echo 'Von: ' . $user->email . ' -> ' . 'Alle';
                }
                echo '</h6>
</div><div class="mdl-card__supporting-text">';
                echo nl2br($nachricht->text);
                echo '</div><div class="mdl-card__supporting-text" style="font-style: italic">
                    ' . date('d.m.Y', strtotime($nachricht->erstellt_am)) . '
                </div></div>';

            }
            ?>
        </div>
        <?php if ($_SESSION['userType']['id'] == 2) { ?>
            <form action="/user/new_message" method="post">
                <div class="mdl-card__actions mdl-card--border">
                    <input type="hidden" id="lektion_id" name="lektion_id" class="lektion_id"
                           value="<?= $lesion->id ?>">
                    <button class="mdl-button mdl-button--colored new_message_button mdl-js-ripple-effect mdl-js-button mdl-button--raised"
                            id="new_message_button">Neue
                        Nachricht
                    </button>
                </div>
            </form>
        <?php } ?>
    </div>
</div>