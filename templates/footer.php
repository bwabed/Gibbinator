</main>
<footer class="mdl-mini-footer mdl-color--grey-700 mdl-color-text--white">
    <div class="mdl-mini-footer__left-section">
        <ul class="mdl-mini-footer__link-list">
            <li class="mdl-color-text--white"><a href="https://github.com/bwabed/Gibbinator" target="_blank">© By Dimitri Waber</a></li>
            <li class="mdl-color-text--white"><a href="http://www.getmdl.io/index.html" target="_blank">Material Design Lite</a></li>
            <li class="mdl-color-text--white"><a href="https://fullcalendar.io/" target="_blank">FullCalendar</a></li>
        </ul>
    </div>
    <?php if (isset($_SESSION ['loggedin']) && $_SESSION ['loggedin'] == true) {?>
        <div class="mdl-mini-footer__right-section">
            <ul class="mdl-mini-footer__link-list">
                <li><?php echo $_SESSION ['user']['vorname'] . ' ' . $_SESSION ['user'] ['nachname']; ?></li>
            </ul>
        </div>
    <?php }?>
</footer>
</div>

<!-- Dieser DIV ist der Snackbar, dank dieser können wir die Meldungen einfach und Elegant ausgeben. -->
<div id="snackbar" class="mdl-snackbar mdl-js-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>
<!-- End Snackbar -->
<?php if (isset($message[0]) && !empty($message[0])): ?>
    <!-- Falls wir einer View eine Message mitgeben, wird er mit Dieser Funktion Ausgegeben -->
    <script type="text/javascript" defer>
        (function() {
            setTimeout( function() {
                'use strict';
                let snackbarContainer = document.querySelector('#snackbar');
                let data = {message: '<?php echo $message[0]; ?>'};
                snackbarContainer.MaterialSnackbar.showSnackbar(data);
            }, 500);
        }());
    </script>
<?php endif; ?>
</body>
</html>