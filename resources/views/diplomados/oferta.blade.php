<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diplomados a inscribirse') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">

        @foreach($diplomados as $diplomado)
        <div class="diplomado flex justify-between items-center border-b border-gray-300 py-4 mb-4">
            <div class="descripcion flex-1 ml-4">
                <h4 class="text-lg font-semibold text-gray-800">{{ $diplomado->nombre }}</h4>
                <label class="text-sm text-gray-600">Tipo</label>
                <p class="tipo text-md text-gray-700">{{ $diplomado->tipo }}</p>

                <label class="text-sm text-gray-600">Categoria</label>
                <p class="clase text-md text-gray-700">{{ $diplomado->clase }}</p>

                <label class="text-sm text-gray-600">Fecha de inicio del diplomado</label>
                <p class="iniciorealizacion text-md text-gray-700">{{ \Carbon\Carbon::parse($diplomado->inicio_realizacion)->format('d/m/Y') }}</p>

                <label class="text-sm text-gray-600">Fecha de terminación del diplomado</label>
                <p class="terminorealizacion text-md text-gray-700">{{ \Carbon\Carbon::parse($diplomado->termino_realizacion)->format('d/m/Y') }}</p>

                <button type="button" class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#modalDescripcion{{ $diplomado->id }}">
                    Ver Descripción
                </button>
            </div>
            <div class="text-center mt-4 flex flex-col gap-2">
                {{-- Verifica si el usuario tiene solicitudes para este diplomado --}}
                @php
                    $tieneSolicitudInstructor = in_array($diplomado->id, $diplomadosConSolicitudInstructor);
                    $tieneSolicitudParticipante = in_array($diplomado->id, $diplomadosConSolicitudParticipante);
                @endphp

                {{-- Botón para participantes --}}
                @if ($user->tipo == 1 && !$tieneSolicitudParticipante && !$tieneSolicitudInstructor)
                    <button type="button" class="btn btn-primary w-full" data-bs-toggle="modal" data-bs-target="#modalParticipante{{ $diplomado->id }}">
                        Inscribirse como participante
                    </button>
                @endif

                {{-- Botón para instructores --}}
                @if ($user->instructor && !$tieneSolicitudInstructor && !$tieneSolicitudParticipante)
                    <button type="button" class="btn btn-primary w-full" data-bs-toggle="modal" data-bs-target="#modalInstructor{{ $diplomado->id }}">
                        Inscribirse como instructor
                    </button>
                @endif

                {{-- Mensaje si ya tiene una solicitud --}}
                @if ($tieneSolicitudInstructor || $tieneSolicitudParticipante)
                    <p class="text-red-500">Ya has solicitado este diplomado.</p>
                @endif
            </div>
        </div>

        <!-- Modal Descripción -->
        <div class="modal fade" id="modalDescripcion{{ $diplomado->id }}" tabindex="-1" aria-labelledby="modalDescripcion{{ $diplomado->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDescripcion{{ $diplomado->id }}Label">Descripción de {{ $diplomado->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $diplomado->objetivo }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Inscribirse como Participante -->
        <div class="modal fade" id="modalParticipante{{ $diplomado->id }}" tabindex="-1" aria-labelledby="modalParticipante{{ $diplomado->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalParticipante{{ $diplomado->id }}Label">Inscripción como Participante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('solicitar_docente_oferta.store', $diplomado->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="pdf">Subir archivo PDF</label>
                            <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
                            <button type="submit" class="btn btn-primary mt-3">Enviar Solicitud</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Inscribirse como Instructor -->
        <div class="modal fade" id="modalInstructor{{ $diplomado->id }}" tabindex="-1" aria-labelledby="modalInstructor{{ $diplomado->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInstructor{{ $diplomado->id }}Label">Inscripción como Instructor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('solicitar_instructor_oferta.store', $diplomado->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="pdf">Subir archivo PDF</label>
                            <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
                            <button type="submit" class="btn btn-primary mt-3">Enviar Solicitud</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        @endforeach
    </div>

</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
