# Example

You can use this as template for new pages.

Use [markdown extra](http://michelf.ca/projects/php-markdown/extra/)

* list
* list and **bold** or *italic*

## heading 2

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.

## Additonal css and js

Additonal css and js (embedded from [cdnjs.com](http://cdnjs.com)) only on this page for [fancybox](http://fancyapps.com/fancybox/).


[![](http://farm4.staticflickr.com/3419/3378131129_bb2123e148_q.jpg)](http://farm4.staticflickr.com/3419/3378131129_bb2123e148.jpg) {.fancybox}

#meta#
pagetitle: Example page
siteTitle: another sitetitle from pagemeta
comment: true
description: Example page, used as template for new pages
d2c: classes for thebody
css:
  - url: //cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css
js:
  - url: "//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"
  - inline: "$(document).ready(function() { $(".fancybox").fancybox(); });" 
