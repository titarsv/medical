@if ($breadcrumbs)
    <section class="siteSection siteSection--noPad siteSection--gray">
        <div class="container">
            <div class="breadcrumbs">
                <ul>
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if (!$breadcrumb->last)
                            <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{{ $breadcrumb->url }}" itemprop="url"><span itemprop="title">{{ $breadcrumb->title }}</span></a></li>
                        @else
                            <li>{{ $breadcrumb->title }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
@endif
