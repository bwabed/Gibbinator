</main>

<!-- Dieser DIV ist der Snackbar, dank dieser können wir die Meldungen einfach und Elegant ausgeben. -->
<div id="snackbar" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>
<!-- End Snackbar -->

<footer class="mdl-mini-footer">
    <div class="mdl-mini-footer__left-section">
        <div class="mdl-logo">Gibbinator WebApp</div>
        <ul class="mdl-mini-footer__link-list">
            <li><a href="/default/about">About Us</a></li>
            <li><a href="http://www.getmdl.io/index.html" target="_blank">Material Design Lite</a></li>
        </ul>
    </div>
    <?php if (isset($_SESSION ['loggedin']) && $_SESSION ['loggedin'] == true) {?>
        <div class="mdl-mini-footer__right-section">
            <ul class="mdl-mini-footer__link-list">
                <li>Eingeloggt als: <a href="/user/edit_profile"><?php echo $_SESSION ['user']['name'] ?></a></li>
            </ul>
        </div>
    <?php }?>
</footer>
</div>
<?php if (isset($message[0]) && !empty($message[0])) { ?>
    <!-- Falls wir einer View eine Message mitgeben, wird er mit Dieser Funktion Ausgegeben -->
    <script>
        (function() {
            setTimeout( function() {
                'use strict';
                var snackbarContainer = document.querySelector('#snackbar');
                var data = {message: '<?php echo $message[0]; ?>'};
                snackbarContainer.MaterialSnackbar.showSnackbar(data);
            },400);
        }());
    </script>
<?php } ?>
</body>
</html>