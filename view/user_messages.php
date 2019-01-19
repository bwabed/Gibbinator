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
        <div class="mdl-card__supporting-text">

        </div>
        <div class="mdl-card__actions">
            <form action="/user/new_message" method="post">
                <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
                <button class="mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised mdl-button--colored"
                        id="add_button" href="/admin/new_message">
                    Neue Nachricht
                </button>
            </form>
        </div>
    </div>
</div>
