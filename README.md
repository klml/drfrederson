drfrederson
=====

A simple static site generator powered by PHP, YAML, Markdown and mustache. Licensed under [MIT License](LICENSE.md)

There are many other and better [staticsitegenerators](http://staticsitegenerators.net), but I searched for these features and did not find:

* edit without texteditor or ssh access. Using __webedit__ on your browser and get a textarea to change and create pages.
* __meta information__ for rendering (template, menue etc) or html metatags are at the __bottom__ of the page and only __optional__
* no indexed or automatic __menu__; just an extra included __page__ with a (nested) list.

Some more small features like titletags from the first heading, etc.

All about editing, admin, setup and templates in the [wiki](https://github.com/klml/drfrederson/wiki/drfrederson).

[Demo](http://drf.grus.uberspace.de/drf:admin) (use user `a` and password `a`. There is __no__ further protection, so all changes will be deleted sporadic for cleanup.)


## TODO


- version source etc
    - https://github.com/blueimp/jQuery-File-Upload
    - template fallback or in init
    - lib: https://github.com/lepture/editor or https://stackedit.io/
- meta to client, need for
  - for new pages (drf-write.jquery.js)
    - sourcepath (from config.yml)
    - prefill
    - source extension for new
  - metaseperator for live preview without meta
  - area, for edit link ?
  - template ?
  - use https://github.com/coolaj86/yamltojson.com, or as special page?
  - secure passwords for upload etc, extra config.secure.yml ?
- Hooks
- plugins
  - Drf automatic ID for headimgs
- live preview without meta
- const public
- http://php.net/manual/de/function.realpath.php PHP 5.3
- [ ] bloggish posttemplate (date, archivcat) for webedit
- [ ] lasttest
- [ ] doku images timestamp

### 2.0
- source versioning
  - [Web_Interfaces](https://git.wiki.kernel.org/index.php/InterfacesFrontendsAndTools#Web_Interfaces)
  - http://stackoverflow.com/questions/7447472/how-could-i-display-the-current-git-branch-name-at-the-top-of-the-page-of-my-de
- tags
- include http://www.mediawiki.org/wiki/Transclusion; Mustache ?
    - new article field (and include)
- listings
    - more cutter
- [ ]* index: dir as link include (for pix)

### Nice2Have
- [ ] minfy als webservice?
- trailing slash
- [ ] etherpad
- redirects with page

### Refactor

- [ ] 404 er save
- [ ] lib https://github.com/michelf/php-markdown extra (needs php 5.3)
