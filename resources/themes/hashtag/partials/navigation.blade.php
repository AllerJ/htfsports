    <a href="#menu" id="menuLink" class="menu-link">
        <img src="/img/hash.png" class="pure-img">
    </a>
    <div id="menu">
        <div class="pure-menu">
            <a class="pure-menu-heading" href="/"><img src="/img/hfs-logo.png" class="pure-img"></a>

            <ul class="pure-menu-list">
                <li class="pure-menu-item menu-item-divided"></li>
                @menu('main', 'cms-frontend::partials.main-menu')
                @if (auth()->user())
                    <li class="pure-menu-item"><a href="{!! url('user/settings') !!}" class="pure-menu-link"><i class="fal fa-user"></i> Settings</a></li>
                    
                    @if (auth()->user()->roles->first()->name == 'admin')
                    <li class="pure-menu-item"><a href="{!! url('admin/dashboard') !!}" class="pure-menu-link"><i class="fal fa-sliders-h"></i> Dashboard</a></li>
                    @endif
                @else
                    <li class="pure-menu-item"><a href="{!! url('login') !!}" class="pure-menu-link"><i class="fal fa-sign-in"></i> Login</a></li>
                @endif
            </ul>
        </div>
    </div>
