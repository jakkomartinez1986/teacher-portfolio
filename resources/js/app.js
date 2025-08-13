import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import esLocale from '@fullcalendar/core/locales/es';

// Exportar para uso global
window.FullCalendar = {
    Calendar,
    dayGridPlugin,
    interactionPlugin,
    timeGridPlugin,
    listPlugin,
    esLocale
};

// Opcional: Inicialización básica si es necesario
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('calendar')) {
        const calendar = new Calendar(document.getElementById('calendar'), {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            locale: esLocale
        });
        calendar.render();
    }
});