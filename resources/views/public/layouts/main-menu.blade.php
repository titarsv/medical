<div class="siteHeader-catalogue">
    <div class="container">
        <div class="row">
            @foreach($items->chunk(ceil($items->count() / 3)) as $item)
                <div class="col-sm-4 col-md-3">
                    <ul>
                        @foreach($item as $i => $cat)
                            <li><a href="{{ $_SERVER['REQUEST_URI'] == '/categories/'.$cat->url_alias ? '#' : env('APP_URL').'/categories/'.$cat->url_alias}}">{!! $cat->name !!}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>
