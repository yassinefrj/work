@extends('layout/app')
@section('title', 'Calendar')
@section('content')
    <div id="calendar" class="mx-auto text-center w-full lg:w-3/4 xl:w-2/3"></div>
@endsection

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          var calendarEl = document.getElementById('calendar');
          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 550,
            headerToolbar: {
                left: 'prev,today,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: @json($events),
            eventClick: function(info) {
                info.jsEvent.preventDefault(); 
                if (info.event.url) {
                    window.open(info.event.url);
                }
            }
          });
          calendar.render();
        });
    </script>
    
@endpush
