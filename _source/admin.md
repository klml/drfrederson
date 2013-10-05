# Administration

## Editing

You can edit all pages in the ''_source/'' directory or with this webeditor.
If you use this site in an public area like your webserver and not in a secure environment on your computer or intranet, you have to get authorizised via htaccess for the JavaScript based edit functionality. After the request for auth you will find on every page an editbutton.

<input type="checkbox" name="requestauth"  id="requestauth" value="requestauth" > Request Auth 

## Create new pages

Call the wished URL, receive the 404 error page and and click edit. Best: create an hyperlink (e.g. in the [menue](_pagedurable)), click and edit.

## Sidebar and page-durable content

Editing sidebar and other page-durable content [pagedurable](_pagedurable) like a ''normal'' page (the concept is well adapted in [Mediawiki Sidebar](https://www.mediawiki.org/wiki/Manual:Interface/Sidebar)).

## Style and JS

CSS and Javascript is maintained in `lib/` as [submodule](http://git-scm.com/book/en/Git-Tools-Submodules)

* [CSS-Mini-Reset](https://github.com/vladocar/CSS-Mini-Reset/)
* [usefulclassroomphrases](https://github.com/klml/usefulclassroomphrases)
* [skeleton](https://github.com/dhg/Skeleton)


### Custom

Edit custom css in [_custom.css](_custom.css)

(the concept is well adapted in [Mediawiki UseSiteCss](https://www.mediawiki.org/wiki/Manual:$wgUseSiteCss) and [SiteJs](https://www.mediawiki.org/wiki/Manual:%24wgUseSiteJs)).


#!
title: Administration
description: All about editing and admin this site
comment: false
