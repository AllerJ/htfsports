<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{!! url(config('cms.backend-route-prefix', 'cms').'/games') !!}">Games</a></li>
            {!! Cms::breadcrumbs($location) !!}
        <li class="active"></li>
    </ol>
</nav>