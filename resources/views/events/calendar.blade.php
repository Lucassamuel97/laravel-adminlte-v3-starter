@extends('layouts.app')

@section('title', 'Agenda')
@section('subtitle', 'Eventos')

{{-- Importa os plugins do AdminLTE do FullCalendar --}}
@section('plugins.FullCalendar', true)
{{-- Importa os plugins do AdminLTE do Moment.js (geralmente já incluso com FullCalendar via AdminLTE, mas bom ter certeza) --}}
@section('plugins.Moment', true)
{{-- Importa os plugins do AdminLTE do Sweetalert2 --}}
@section('plugins.Sweetalert2', true)

@section('content_header_title', 'Agenda')
@section('content_header_subtitle', 'Eventos')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Seus Eventos</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#eventModal" id="addEventBtn">
                    Adicionar Evento
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id='calendar'></div>
        </div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Adicionar Novo Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        @csrf
                        <input type="hidden" id="eventId" name="id">
                        <div class="form-group">
                            <label for="eventTitle">Título do Evento</label>
                            <input type="text" class="form-control" id="eventTitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="eventDescription">Descrição</label>
                            <textarea class="form-control" id="eventDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="eventStart">Início</label>
                            <input type="datetime-local" class="form-control" id="eventStart" name="start" required>
                        </div>
                        <div class="form-group">
                            <label for="eventEnd">Fim</label>
                            <input type="datetime-local" class="form-control" id="eventEnd" name="end" required>
                        </div>
                        <div class="form-group">
                            <label for="eventColor">Cor do Evento</label>
                            <input type="color" class="form-control" id="eventColor" name="color">
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveEventBtn">Salvar Evento</button>
                        <button type="button" class="btn btn-danger float-right" id="deleteEventBtn" style="display:none;">Excluir Evento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar;

            // Função para pegar a data/hora de um input datetime-local e converter para UTC
            function getUtcDateTimeFromInput(elementId) {
                var localDateTimeString = $('#' + elementId).val(); // Ex: "2025-06-22T10:00"
                if (!localDateTimeString) return null;
                // Parse a string local como um objeto Moment local
                var localMoment = moment(localDateTimeString);
                // Converta para UTC e formate para o banco de dados (Laravel)
                return localMoment.utc().format('YYYY-MM-DD HH:mm:ss');
            }

            // Função para pegar a data/hora de um objeto FullCalendar Event e converter para UTC
            function getUtcDateTimeFromEvent(momentObject) {
                if (!momentObject) return null;
                // Converta para UTC e formate para o banco de dados (Laravel)
                return momentObject.utc().format('YYYY-MM-DD HH:mm:ss');
            }

            // Configurações do FullCalendar
            calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialDate: new Date(),
                navLinks: true,
                editable: true,
                selectable: true,
                nowIndicator: true,
                dayMaxEvents: true,
                locale: 'pt-br',
                events: "{{ route('api.events') }}",

                eventClick: function(info) {
                    $('#eventModalLabel').text('Editar Evento');
                    $('#eventId').val(info.event.id);
                    $('#eventTitle').val(info.event.title);
                    $('#eventDescription').val(info.event.extendedProps.description || '');
                    // Exibir as datas no formato local para o input datetime-local
                    $('#eventStart').val(moment(info.event.start).format('YYYY-MM-DDTHH:mm'));
                    $('#eventEnd').val(moment(info.event.end).format('YYYY-MM-DDTHH:mm'));
                    $('#eventColor').val(info.event.backgroundColor || '#3788d8');
                    $('#deleteEventBtn').show();
                    $('#eventModal').modal('show');
                },

                select: function(info) {
                    $('#eventModalLabel').text('Adicionar Novo Evento');
                    $('#eventId').val('');
                    $('#eventForm')[0].reset();
                    // Preenche com a seleção, formatando para o input datetime-local
                    $('#eventStart').val(moment(info.startStr).format('YYYY-MM-DDTHH:mm'));
                    $('#eventEnd').val(moment(info.endStr).format('YYYY-MM-DDTHH:mm'));
                    $('#deleteEventBtn').hide();
                    $('#eventModal').modal('show');
                },

                eventDrop: function(info) {
                    var eventData = {
                        title: info.event.title,
                        description: info.event.extendedProps.description,
                        color: info.event.backgroundColor,
                        // CONVERTER PARA UTC ANTES DE ENVIAR
                        start: getUtcDateTimeFromEvent(moment(info.event.start)),
                        end: getUtcDateTimeFromEvent(moment(info.event.end)),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };
                    updateEvent(info.event.id, eventData);
                },

                eventResize: function(info) {
                    var eventData = {
                        title: info.event.title,
                        description: info.event.extendedProps.description,
                        color: info.event.backgroundColor,
                        // CONVERTER PARA UTC ANTES DE ENVIAR
                        start: getUtcDateTimeFromEvent(moment(info.event.start)),
                        end: getUtcDateTimeFromEvent(moment(info.event.end)),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };
                    updateEvent(info.event.id, eventData);
                }
            });

            calendar.render();

            // Adicionar evento pelo botão
            $('#addEventBtn').on('click', function() {
                $('#eventModalLabel').text('Adicionar Novo Evento');
                $('#eventId').val('');
                $('#eventForm')[0].reset();
                var now = moment().format('YYYY-MM-DDTHH:mm');
                $('#eventStart').val(now);
                $('#eventEnd').val(moment().add(1, 'hour').format('YYYY-MM-DDTHH:mm'));
                $('#deleteEventBtn').hide();
            });

            // Lidar com o envio do formulário (Salvar/Atualizar)
            $('#eventForm').on('submit', function(e) {
                e.preventDefault();

                var eventId = $('#eventId').val();
                var url = eventId ? '/api/events/' + eventId : '/api/events';
                var method = eventId ? 'PUT' : 'POST';

                var eventData = {
                    title: $('#eventTitle').val(),
                    description: $('#eventDescription').val(),
                    color: $('#eventColor').val(),
                    // CONVERTER PARA UTC ANTES DE ENVIAR
                    start: getUtcDateTimeFromInput('eventStart'),
                    end: getUtcDateTimeFromInput('eventEnd'),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                $.ajax({
                    url: url,
                    type: method,
                    data: eventData,
                    success: function(response) {
                        $('#eventModal').modal('hide');
                        calendar.refetchEvents();
                        Swal.fire('Sucesso!', 'Evento salvo com sucesso!', 'success');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Erro!', 'Ocorreu um erro ao salvar o evento.', 'error');
                    }
                });
            });

            // Lidar com a exclusão do evento
            $('#deleteEventBtn').on('click', function() {
                var eventId = $('#eventId').val();
                if (confirm('Tem certeza que deseja excluir este evento?')) {
                    $.ajax({
                        url: '/api/events/' + eventId,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#eventModal').modal('hide');
                            calendar.refetchEvents();
                            Swal.fire('Excluído!', 'Evento excluído com sucesso!', 'success');
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire('Erro!', 'Ocorreu um erro ao excluir o evento.', 'error');
                        }
                    });
                }
            });

            function updateEvent(eventId, eventData) {
                $.ajax({
                    url: '/api/events/' + eventId,
                    type: 'PUT',
                    data: eventData,
                    success: function(response) {
                        Swal.fire('Sucesso!', 'Evento atualizado com sucesso!', 'success');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Erro!', 'Ocorreu um erro ao atualizar o evento.', 'error');
                        calendar.refetchEvents();
                    }
                });
            }
        });
    </script>
@stop