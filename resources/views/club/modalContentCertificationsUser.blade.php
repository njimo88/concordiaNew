@if ($user->certifications->isEmpty())
    <p class="text-center text-muted">Aucune certification trouvée pour cet utilisateur.</p>
@else
    @foreach ($user->certifications as $certification)
        <div class="row align-items-center">
            <!-- Affichage Certifs -->
            <div class="col-md-6 d-flex align-items-center justify-content-start">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="{{ $certification->level->color }}"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon points="12,2 15,10 24,10 17,15 19,24 12,19 5,24 7,15 0,10 9,10" />
                </svg>
                <span class="ms-2">
                    {{ $certification->discipline->name }} {{ $certification->level->name }}
                    @if ($certification->points)
                        ({{ $certification->points }}pts)
                    @endif
                </span>
            </div>

            <!-- Historique Certifs -->
            <div class="col-md-6">
                @if ($certification->updated_by)
                    <p class="mb-0">Le
                        {{ Carbon\Carbon::parse($certification->exam_date)->format('d/m/Y') }} par
                        {{ $certification->updater->lastname }}
                        {{ $certification->updater->name }}
                    </p>
                    </p>
                @else
                    <p class="mb-0">Le
                        {{ Carbon\Carbon::parse($certification->exam_date)->format('d/m/Y') }} par
                        {{ $certification->creator->lastname }}
                        {{ $certification->creator->name }}
                    </p>
                @endif
            </div>

        </div>

        <!-- Affichage de la ligne HR si ce n'est pas la dernière certification -->
        @if (!$loop->last)
            <hr class="mt-2 mb-2" style="border-top: dotted 5px; background-color: transparent">
        @endif
    @endforeach
@endif
