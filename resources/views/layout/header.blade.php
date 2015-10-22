{{--
    Copyright 2015 ppy Pty. Ltd.

    This file is part of osu!web. osu!web is distributed with the hope of
    attracting more community contributions to the core ecosystem of osu!.

    osu!web is free software: you can redistribute it and/or modify
    it under the terms of the Affero GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
--}}
<nav class="flex-none no-print">
    <!-- Specific style for smaller displays (smartphone) -->
    <div class="visible-xs">
        <div class="navbar-mobile navbar navbar-default navbar-static-top bg--{{ $current_section }}" role="navigation">
            <div class="container">
                <div class="navbar-header navbar-mobile__header">
                    <div class="navbar-mobile__header-section">
                        <a class="navbar-mobile__logo" href="/"></a>
                        <span class="navbar-mobile__brand navbar-brand">
                            {{ trans("layout.menu.$current_section.$current_action") }}
                        </span>
                    </div>

                    <div class="navbar-mobile__header-section">
                        @if (Auth::check())
                            <a
                                href="{{ route('users.show', Auth::user()->user_id) }}"
                                class="avatar avatar--navbar-mobile js-navbar-mobile--top-icon"
                                style="background-image: url('{{ Auth::user()->user_avatar }}');"
                            >
                            </a>
                        @else
                            <a
                                href="#"
                                data-toggle="modal"
                                data-target="#user-dropdown-modal"
                                title="{{ trans('users.anonymous.login') }}"
                                class="avatar avatar--navbar-mobile avatar--guest js-navbar-mobile--top-icon"
                            >
                            </a>
                        @endif

                        <button
                            type="button"
                            class="navbar-toggle navbar-mobile__toggle colour-hover--{{ $current_section }}"
                            data-toggle="collapse" data-target="#xs-navbar"
                        >
                            <span class="sr-only">Toggle navigation</span>
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse navbar-mobile__menu js-navbar-mobile--menu" id="xs-navbar">
            <ul class="nav navbar-nav navbar-mobile__menu-items">
                <li class="navbar-mobile__user">
                    @if (Auth::check())
                        <a class="navbar-mobile__menu-item navbar-mobile__menu-item--user" href="{{ route('users.show', Auth::user()) }}">
                            <span
                                class="avatar avatar--navbar-mobile"
                                style="background-image: url('{{ Auth::user()->user_avatar }}');">
                            </span>

                            {{ Auth::user()->username }}
                        </a>

                        <a
                            class="navbar-mobile__menu-item navbar-mobile__menu-item--logout js-logout-link"
                            href="{{ route('users.logout') }}"
                            data-method="delete"
                            data-confirm="{{ trans('users.logout_confirm') }}"
                            data-remote="1"
                        >
                            <i class="fa fa-sign-out"></i>
                        </a>
                    @else
                        <a class="navbar-mobile__menu-item navbar-mobile__menu-item--user" href="#" title="{{ trans('users.anonymous.login') }}" data-toggle="modal" data-target="#user-dropdown-modal">
                            <span class="avatar avatar--guest avatar--navbar-mobile"></span>

                            {{ trans('users.anonymous.username') }}
                        </a>
                    @endif
                </li>
                @foreach (nav_links() as $section => $links)
                <li class="dropdown">
                    <a data-toggle="dropdown" role="button" data-target="#" id="expand-{{ $section }}" class="navbar-mobile__menu-item dropdown-toggle" href="{{ array_values($links)[0] }}">
                        <i class="fa fa-chevron-right navbar-mobile__menu-item-icon navbar-mobile__menu-item-icon--closed"></i>
                        <i class="fa fa-chevron-down navbar-mobile__menu-item-icon navbar-mobile__menu-item-icon--opened"></i>
                        {{ trans("layout.menu.$section._") }}
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="expand-{{ $section }}">
                        @foreach ($links as $action => $link)
                        <li>
                            <a class="navbar-mobile__menu-subitem" href="{{ $link }}" data-toggle="collapse" data-target="#xs-navbar">
                                <i class="fa fa-chevron-right navbar-mobile__menu-subitem-icon"></i>
                                {{ trans("layout.menu.$section.$action") }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="hidden-xs content content--compact">
        <!-- Main style -->
        <div class="nav-bg bg--{{ $current_section }}">
            <div class="nav-bg__triangles nav-bg__triangles--1"></div>
            <div class="nav-bg__triangles nav-bg__triangles--2"></div>
            <div class="nav-bg__triangles nav-bg__triangles--3"></div>
            <div class="nav-bg__overlay nav-bg__overlay--{{ $current_section }}"></div>
        </div>

        <div class="content__row"><div class="nav">
            <a class="nav__logo" href="/"></a>

            <div class="nav__links js-nav--links">
                <div class="nav__menu">
                    @foreach (nav_links() as $section => $links)
                        <a
                            class="js-nav--link nav__link {{ $section === $current_section ? 'nav__link--active' : '' }}"
                            href="{{ array_values($links)[0] }}" data-section="{{ $section }}"
                        >
                            {{ trans("layout.menu.$section._") }}
                        </a>
                    @endforeach

                    <a class="nav__link nav__link--support" href="{{ config("osu.urls.support-the-game") }}" target="_blank">
                        support the game
                    </a>

                    <a class="nav__link" href="{{ config("osu.urls.social.facebook") }}" target="_blank">
                        <i class="fa fa-facebook-f"></i>
                    </a>

                    <a class="nav__link" href="{{ config("osu.urls.social.twitter") }}" target="_blank">
                        <i class="fa fa-twitter"></i>
                    </a>
                </div>

                <div class="js-nav--title nav__title">
                    <div class="nav__submenu-container nav__submenu-container--page-title" data-type="page-title">
                        <div class="nav__submenu">
                            <span class="nav__page-title">{{ trans("layout.menu.$current_section._") }}</span>
                            <span class="nav__page-title nav__page-title--action">{{ trans("layout.menu.$current_section.$current_action") }}</span>
                        </div>
                    </div>

                    @foreach (nav_links() as $section => $links)
                        <div class="nav__submenu-container" data-type="submenu" data-section="{{ $section }}">
                            <div class="nav__submenu">
                                <span class="nav__submenu-link nav__submenu-link--section">
                                    {{ trans("layout.menu.$section._") }}
                                </span>

                                @foreach ($links as $action => $link)
                                    <a class="nav__submenu-link" href="{{ $link }}">
                                        {{ trans("layout.menu.$section.$action") }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex-none nav-user-bar-container">
                @include("objects.user-dropdown")
            </div>
        </div></div>
    </div>
</nav>

<div id="popup-container">
    <div class="alert alert-dismissable popup-clone col-md-6 col-md-offset-3 text-center" style="display: none">
        <button type="button" data-dismiss="alert" class="close"><i class="fa fa-close"></i></button>
        <span class="popup-text"></span>
    </div>
</div>
<div class="loading-overlay">
    <div class="loading-overlay__container">
        @foreach (range(1, 4) as $n)
            <div class="loading-overlay__follow-point
                    loading-overlay__follow-point--{{ $n }}">
                ›
            </div>

            @foreach (['approach', 'hit'] as $type)
                <div class="loading-overlay__circle
                        loading-overlay__circle--{{ $n }}
                        loading-overlay__circle--{{ $type }}"
                ></div>
            @endforeach
        @endforeach
    </div>
</div>
