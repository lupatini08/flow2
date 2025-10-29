document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap5',
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            selectable: true,
            editable: true,
            events: 'get_events.php',
            dateClick: function(info) {
                alert('Você clicou em: ' + info.dateStr);
            },
            eventClick: function(info) {
                alert('Evento: ' + info.event.title + '\nDescrição: ' + (info.event.extendedProps.description || 'Sem descrição'));
            }
        });
        calendar.render();
    }
});