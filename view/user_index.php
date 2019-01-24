<script type="text/javascript">
    $(document).ready(function () {

            $('#calendar').fullCalendar({
                eventClick: function(eventObj) {
                    $.post("/")
                },
                events: [

                ],
                selectable: true,
                locale: 'de',
                weekNumbers: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                dayClick: function (date) {
                    $('#calendar').fullCalendar('changeView', 'agendaDay', date.format());
                }

            })

        }
    );
</script>
<div class="mdl-grid mdl-layout__content">
    <div class="mdl-card mdl-cell mdl-cell--9-col mdl-shadow--2dp mdl-color--white">
        <div class="mdl-card__title mdl-color--grey-500">
            <h1>Kalender</h1>
        </div>
        <div id="calendar"></div>
    </div>
    <div class="mdl-cell mdl-cell--3-col mdl-cell--12-col-tablet mdl-cell--12-col-phone mdl-grid">
        <div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
            <div class="mdl-card__title mdl-card--expand mdl-color--teal-300">
                <h2 class="mdl-card__title-text">Updates</h2>
            </div>
            <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                Non dolore elit adipisicing ea reprehenderit consectetur culpa.
            </div>
            <div class="mdl-card__actions mdl-card--border">
                <a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect">Read More</a>
            </div>
        </div>
    </div>
</div>