@foreach($links as $link)
    @if ($link->external)
        <li class="pure-menu-item"><a href="{{ url($link->external_url) }}" class="pure-menu-link">{{ $link->name }}</a></li>
    @else
        <li class="pure-menu-item"><a href="{{ url($link->external_url) }}" class="pure-menu-link">{{ $link->name }}</a></li>
    @endif
@endforeach