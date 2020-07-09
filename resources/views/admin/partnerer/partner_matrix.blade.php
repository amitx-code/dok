
@foreach ($matrix->levels as $level)
    <div class="row">
        <div class="col-sm-1">{{ $level->level}} уровень</div>
        <div class="col-sm-1">total: {{ $level->total }}</div>
        @foreach ($level->tariffs as $tarif)
            <div class="col-sm-1">
                @if ($tarif->count)
                    <a href="?level={{ $level->level }}&tarif={{ $tarif->code }}">{{ $tarif->prefix }} {{ $tarif->count }}</a>
                @else
                    {{ $tarif->prefix }} {{ $tarif->count }}
                @endif
            </div>
        @endforeach
        <div class="col-sm-1">
            @if ($level->isActive)
                активен
            @else
                активировать
            @endif
        </div>
    </div>
@endforeach

<div class="row">
    <div class="col-sm-1">Итого</div>
    <div class="col-sm-1">total: {{ $matrix->total }}</div>
    @foreach ($matrix->tariffs as $tarif)
        <div class="col-sm-1">{{ $tarif->prefix }} {{ $tarif->count }}</div>
    @endforeach
    <div class="col-sm-1">
    </div>
</div>


@if (!empty($subUsers))
    <h3>Пользователи</h3>
    <ol>
    @foreach ($subUsers as $subUser)
        <li>
            {{ $subUser->name }}
        </li>
    @endforeach
    </ol>
@endif