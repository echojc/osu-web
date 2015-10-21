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
$(document).on 'shown.bs.modal', '.modal', (e) ->
  $(e.target).find('.modal-af').focus()

$(document).on 'hidden.bs.modal', '.modal', ->
  $('.modal-backdrop').remove()
  $('body, .js-fixed-element').css paddingRight: ''

$(document).on 'show.bs.modal', '.modal', ->
  alignments = {}
  elements = document.getElementsByClassName('js-fixed-element')

  for el, i in elements
    alignments[i] = el.getBoundingClientRect()

  for el, i in elements
    el.style.left = "#{alignments[i].left}px"
    el.style.top = "#{alignments[i].top}px"
    el.style.width = "#{alignments[i].width}px"

$(document).on 'shown.bs.modal', '.modal', ->
  paddingRight = $('body').css('padding-right')

  for el in document.getElementsByClassName('js-fixed-element')
    el.style.paddingRight = paddingRight
    el.style.left = ''
    el.style.top = ''
    el.style.width = ''
