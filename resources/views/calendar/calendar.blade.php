@extends('layout/app')
@section('title', 'Calendar')
@section('content')
    <div id="calendar" class="mx-auto text-center w-full lg:w-3/4 xl:w-2/3"></div>

    <div class="my-4">
        <label for="icsLink">Link to the calendar</label>
        <div class="input-group">
            <?php
                //$baseUrl = str_replace('www.', '', env('APP_URL'));
                $baseUrl = str_replace("https:", "", config('app.url'));
                $linkToEventl = $baseUrl . '/calendar/download?user=' . auth()->user()->id;
            ?>
            <input id="icsLink" class="form-control" value="{{$linkToEventl}}" readonly>
            <button id="copyIcsButton" class="btn btn-dark ml-1" aria-label="copy the link">
                <i class="fa fa-clipboard fa-fw" aria-hidden="true"></i>
                Copy
            </button>
            <!-- Add to Google Calendar button -->
            <!--  <a id="addToGoogleCalendarButton" class="btn btn-primary"  href="" target="_blank">Add to Google Calendar</a>  -->
            <?php
                //$baseUrl = str_replace('www.', '', env('APP_URL'));
                $baseUrl = str_replace("https:", "", config('app.url'));
                $linkToEvent = '//www.google.com/calendar/render?cid=webcal:' . $baseUrl . '/calendar/download?user=' . auth()->user()->id;
            ?>

            <a id="addToCalendar" class="btn btn-dark ml-1" href="{{$linkToEvent}}" target="_blank" aria-label="Ajouter au calendrier" rel="noreferrer">Ajouter au calendrierG</a>
            <div class='valid-feedback d-block' id='sucess'></div>
            
           
            
        </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
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

            // Initialize Clipboard.js
            var clipboard = new ClipboardJS('#copyIcsButton', {
                target: function(trigger) {
                    return document.getElementById('icsLink');
                }
            });

            clipboard.on('success', function(e) {
                console.log(e);
               // alert('Link copied to clipboard!');
               $('#sucess').empty();
               $('#sucess').append("The link has been successfully copied to your clipboard.");
            });

            clipboard.on('error', function(e) {
                console.error(e);
                alert('Failed to copy link to clipboard.');
            });
        });


    </script>
    
@endpush
