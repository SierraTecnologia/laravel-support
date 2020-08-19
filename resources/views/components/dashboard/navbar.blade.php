<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="hamburger btn-link">
                <span class="hamburger-inner"></span>
            </button>
            @section('breadcrumbs')
            <ol class="breadcrumb hidden-xs">
                @php
                $segments = array_filter(explode('/', str_replace(route('rica.dashboard'), '', Request::url())));
                $url = route('rica.dashboard');
                @endphp
                @if(count($segments) == 0)
                    <li class="active"><i class="facilitador-boat"></i> {{ __('facilitador::generic.dashboard') }}</li>
                @else
                    <li class="active">
                        <a href="{{ route('rica.dashboard')}}"><i class="facilitador-boat"></i> {{ __('facilitador::generic.dashboard') }}</a>
                    </li>
                    @foreach ($segments as $segment)
                        @php
                        $url .= '/'.$segment;
                        @endphp
                        @if ($loop->last)
                            <li>{{ \Support\Routing\UrlGenerator::displayStringName($segment) }}</li>
                        @else
                            <li>
                                <a href="{{ $url }}">{{ \Support\Routing\UrlGenerator::displayStringName($segment) }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ol>
            @show
        </div>
        <ul class="nav navbar-nav @if (__('facilitador::generic.is_rtl') == 'true') navbar-left @else navbar-right @endif">
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                   aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span
                            class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-animated">
                    @if(Auth::user())
                        <li class="profile-img">
                            <img src="{{ $user_avatar }}" class="profile-img">
                            <div class="profile-body">
                                <h5>{{ Auth::user()->name }}</h5>
                                <h6>{{ Auth::user()->email }}</h6>
                            </div>
                        </li>
                    @endif
                    <li class="divider"></li>
                    <?php $navItens = (new \Support\Template\Mounters\SystemMount())->loadMenuForArray(); ?>
                    @if(is_array($navItens) && !empty($navItens))
                        @foreach($navItens as $name => $item)
                            @if(is_string($item))
                                <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                                    {{__($item)}}
                                </li>
                            @else
                                <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                                    @if(isset($item['route']) && $item['route'] == 'facilitador.logout')
                                    <form action="{{ route('facilitador.logout') }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger btn-block">
                                            @if(isset($item['icon']) && !empty($item['icon']))
                                                <i class="{!! $item['icon'] !!}"></i>
                                            @elseif(isset($item['icon_class']) && !empty($item['icon_class']))
                                                <i class="{!! $item['icon_class'] !!}"></i>
                                            @endif
                                            @if(isset($item['label']) && !empty($item['label']))
                                                {!! $item['label'] !!}
                                            @else
                                                {{__($name)}}
                                            @endif
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                                        @if(isset($item['icon']) && !empty($item['icon']))
                                            <i class="{!! $item['icon'] !!}"></i>
                                        @elseif(isset($item['icon_class']) && !empty($item['icon_class']))
                                            <i class="{!! $item['icon_class'] !!}"></i>
                                        @endif
                                        @if(isset($item['label']) && !empty($item['label']))
                                            {!! $item['label'] !!}
                                        @else
                                            {{__($name)}}
                                        @endif
                                    </a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</nav>
