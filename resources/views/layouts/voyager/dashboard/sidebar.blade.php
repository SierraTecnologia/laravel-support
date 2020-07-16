<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('facilitador.dashboard') }}">
                    <div class="logo-icon-container">
                        <?php $admin_logo_img = Facilitador::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img src="{{ facilitador_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img src="{{ Facilitador::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                    </div>
                    <div class="title">{{Facilitador::setting('admin.title', 'VOYAGER')}}</div>
                </a>
            </div><!-- .navbar-header -->

            <div class="panel widget center bgimage"
                 style="background-image:url({{ Facilitador::image( Facilitador::setting('admin.bg_image'), facilitador_asset('images/bg.jpg') ) }}); background-size: cover; background-position: 0px;">
                <div class="dimmer"></div>
                <div class="panel-content">
                    @if(Auth::user())
                        <img src="{{ $user_avatar }}" class="avatar" alt="{{ Auth::user()->name }} avatar">
                        <h4>{{ ucwords(Auth::user()->name) }}</h4>
                        <p>{{ Auth::user()->email }}</p>

                        <a href="{{ route('profile.profile') }}" class="btn btn-primary">{{ __('facilitador::generic.profile') }}</a>
                        <div style="clear:both"></div>
                    @endif
                </div>
            </div>

        </div>
        <div id="adminmenu">
            <admin-menu :items=""></admin-menu>
        </div>
    </nav>
</div>
