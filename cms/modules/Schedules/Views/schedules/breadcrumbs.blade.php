<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{!! url(config('cms.backend-route-prefix', 'cms').'/schedules') !!}">Schedules</a></li>
            {!! Cms::breadcrumbs($location) !!}
        <li class="active"></li>
    </ol>
</nav>