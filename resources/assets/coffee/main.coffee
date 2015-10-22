###
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
###
animation_delay = 80
headerHovered = false
rootUrl = History.getRootUrl()
scrollOptions =
  duration: 800
  easing: 'swing'
itemHovered = false
lastItemChange = null

el = React.createElement

# loading animation overlay
# fired from turbolinks
$(document).on 'page:fetch', osu.showLoadingOverlay
$(document).on 'page:receive', osu.hideLoadingOverlay
# form submission is not covered by turbolinks
$(document).on 'submit', 'form', osu.showLoadingOverlay


$(document).on 'ready page:load', =>
  @stickyHeader ||= new StickyHeader
  @globalDrag ||= new GlobalDrag
  @gallery ||= new Gallery


$(document).on 'ready page:load osu:page:change', ->
  osu.initTimeago()
  new Layzr


$(document).on 'ready page:load', =>
  return if currentUser.id == undefined
  React.render el(UserCard), $('.js-user-dropdown-modal__dialog')[0]


$(document).on 'change', '.js-url-selector', (e) ->
  $target = $(e.target)
  osu.navigate $target.val(), $target.attr('data-keep-scroll') == '1'

$(document).on 'ready page:load', ->
  $navTitle = $('.js-nav--title')
  $navPageTitle = $navTitle.find('[data-type="page-title"]')
  $submenu = $navTitle.find('[data-type="submenu"]')

  linkMouseIn = -> headerHovered = true
  linkMouseOut = ->
    headerHovered = false
    return unless $navPageTitle.is(':hidden')

    revertTitle = ->
      return if headerHovered
      $navPageTitle.fadeIn animation_delay * 2
      $submenu.fadeOut animation_delay * 3
    window.setTimeout revertTitle, 400

  $('.js-nav--links').hover linkMouseIn, linkMouseOut


  menuItemMouseIn = (e) ->
    section = $(e.target).attr('data-section')
    $currentSubmenu = $submenu.filter("[data-section='#{section}']")
    itemHovered = true
    window.clearTimeout lastItemChange

    hideTitle = ->
      return unless itemHovered
      $navPageTitle.fadeOut animation_delay * 1.5
      $submenu.fadeOut animation_delay * 1.5
      $currentSubmenu.stop().fadeIn animation_delay

    lastItemChange = window.setTimeout hideTitle, 100

  menuItemMouseOut = -> itemHovered = false

  $('.js-nav--link').hover menuItemMouseIn, menuItemMouseOut


# Internal Helper
$.expr[':'].internal = (obj, index, meta, stack) ->
  # Prepare
  $this = $(obj)
  url = $this.attr('href') or ''
  url.substring(0, rootUrl.length) == rootUrl or url.indexOf(':') == -1

$.fn.moddify = ->
  regex = /(\d\d:\d\d:\d\d\d(?: \([0-9,#&;\|]+\))*)/ig
  $(this).each ->
    $(this).html $(this).html().replace(regex, '<code><a class="osu-modtime" href="osu://edit/$1" rel="nofollow">$1</a></code>')
  $ this

$.fn.linkify = ->
  regex = /(https?:\/\/(?:(?:[a-z0-9]\.|[a-z0-9][a-z0-9-]*[a-z0-9]\.)*[a-z][a-z0-9-]*[a-z0-9](?::\d+)?)(?:(?:(?:\/+(?:[a-z0-9$_\.\+!\*',;:@&=-]|%[0-9a-f]{2})*)*(?:\?(?:[a-z0-9$_\.\+!\*',;:@&=-]|%[0-9a-f]{2})*)?)?(?:#(?:[a-z0-9$_\.\+!\*',;:@&=-]|%[0-9a-f]{2})*)?)?)/ig
  $(this).each ->
    $(this).html $(this).html().replace(regex, '<a href="$1" rel="nofollow" target="_blank">$1</a>')
  $ this
