<!-- {{--
	<li class="nav-item @if ($menuActiveUrl === ($item->url ?? false)) open @endif" data-type="{{ $item->type ?? 'node' }}">
    <a href="{{ $item->url ?? '#' }}">
        <i class="{{ $item->icon }}"></i>
        <span class="title">{{ $item->name }}</span>
        @if (!empty($item->items))
            <span class="setClass arrow"></span>
        @endif
    </a>

    @if (!empty($item->items))
        <ul class="sub-menu">
            @foreach ($item->items as $menuItem)
                @include('admin.menu_item', ['item' => $menuItem])
            @endforeach
        </ul>
    @endif
</li>
--}} -->
