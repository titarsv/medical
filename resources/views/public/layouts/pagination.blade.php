@if ($paginator->lastPage() > 1)
    <div id="paginator">
        @if($paginator->currentPage() > 1)
            <a href="{{ preg_replace('/(.+)\?page=1$/i', '$1', $paginator->url($paginator->currentPage()-1)) }}" class="pagor{{ ($paginator->currentPage() == 1) ? ' selected' : '' }}">Назад</a>
        @endif

        @if($paginator->lastPage() <= 11)

            @for ($c=1; $c<=$paginator->lastPage(); $c++)
                <a href="{{ preg_replace('/(.+)\?page=1$/i', '$1', $paginator->url($c)) }}" class="pagor{{ ($paginator->currentPage() == $c) ? ' selected' : '' }}">{{ $c }}</a>
            @endfor

        @elseif($paginator->currentPage() < 7)

            @for ($c=1; $c<=10; $c++)
                <a href="{{ $paginator->url($c) }}" class="pagor{{ ($paginator->currentPage() == $c) ? ' selected' : '' }}">{{ $c }}</a>
            @endfor

            @if($paginator->lastPage() >= 20)
                <a href="{{ $paginator->url(($paginator->lastPage()-10)/2 + 10 - ($paginator->lastPage()-10)%2/2) }}" class="pagor{{ ($paginator->currentPage() == ($paginator->lastPage()-10)/2 + 10 - ($paginator->lastPage()-10)%2/2) ? ' selected' : '' }}">{{ ($paginator->lastPage()-10)/2 + 10 - ($paginator->lastPage()-10)%2/2 }}</a>
            @endif

            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagor{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' selected' : '' }}">{{ $paginator->lastPage() }}</a>

        @elseif($paginator->currentPage() > ($paginator->lastPage()-6))

            <a href="{{ preg_replace('/(.+)\?page=1$/i', '$1', $paginator->url(1)) }}" class="pagor{{ ($paginator->currentPage() == 1) ? ' selected' : '' }}">{{ 1 }}</a>

            @if($paginator->lastPage() >= 20)
                <a href="{{ $paginator->url(($paginator->lastPage()-8)/2 - ($paginator->lastPage()-10)%2/2) }}" class="pagor{{ ($paginator->currentPage() == ($paginator->lastPage()-8)/2 - ($paginator->lastPage()-10)%2/2) ? ' selected' : '' }}">{{ ($paginator->lastPage()-8)/2 - ($paginator->lastPage()-10)%2/2 }}</a>
            @endif

            @for ($c=($paginator->lastPage()-9); $c<=$paginator->lastPage(); $c++)
                <a href="{{ $paginator->url($c) }}" class="pagor{{ ($paginator->currentPage() == $c) ? ' selected' : '' }}">{{ $c }}</a>
            @endfor

        @else

            <a href="{{ preg_replace('/(.+)\?page=1$/i', '$1', $paginator->url(1)) }}" class="pagor{{ ($paginator->currentPage() == 1) ? ' selected' : '' }}">{{ 1 }}</a>

            @if($paginator->currentPage() > 10)
                <a href="{{ $paginator->url(($paginator->currentPage()-3)/2 - ($paginator->currentPage()-3)%2/2) }}" class="pagor{{ ($paginator->currentPage() == ($paginator->currentPage()-3)/2 - ($paginator->currentPage()-3)%2/2) ? ' selected' : '' }}">{{ ($paginator->currentPage()-3)/2 - ($paginator->currentPage()-3)%2/2 }}</a>
            @endif

            @for ($c=($paginator->currentPage()-4); $c<=($paginator->currentPage()+4); $c++)
                <a href="{{ $paginator->url($c) }}" class="pagor{{ ($paginator->currentPage() == $c) ? ' selected' : '' }}">{{ $c }}</a>
            @endfor

            @if($paginator->currentPage() < $paginator->lastPage()-10)
                <a href="{{ $paginator->url(($paginator->lastPage()-$paginator->currentPage() -4)/2 + $paginator->currentPage() + 4 - ($paginator->lastPage()-$paginator->currentPage() -4)%2/2) }}" class="pagor{{ ($paginator->currentPage() == (($paginator->lastPage()-$paginator->currentPage() -4)/2 + $paginator->currentPage() + 4 - ($paginator->lastPage()-$paginator->currentPage() -4)%2/2)) ? ' selected' : '' }}">{{ ($paginator->lastPage()-$paginator->currentPage() -4)/2 + $paginator->currentPage() + 4 - ($paginator->lastPage()-$paginator->currentPage() -4)%2/2 }}</a>
            @endif

            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagor{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' selected' : '' }}">{{ $paginator->lastPage() }}</a>

        @endif

        @if($paginator->currentPage() < $paginator->lastPage())
            <a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="pagor">Вперёд</a>
        @endif

        {{--@for ($i = 1; $i <= $paginator->lastPage(); $i++)--}}
        {{--<a href="{{ $paginator->url($i) }}" class="pagor{{ ($paginator->currentPage() == $i) ? ' selected' : '' }}">{{ $i }}</a>--}}
        {{--@endfor--}}
        <div style="clear:both;"></div>
    </div>
@endif