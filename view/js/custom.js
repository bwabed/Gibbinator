$(document).ready(function (eventArray) {

        $('#calendar').fullCalendar({
            eventClick: function(eventObj) {
                $.post("/")
            },
            events: eventArray,
            selectable: true,
            locale: 'de',
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
