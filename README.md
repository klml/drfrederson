drfrederson
=====

A simple static site generator powered by PHP, YAML, Markdown and mustache. Based on [github.com/lonescript/php-site-maker](http://github.com/lonescript/php-site-maker)


There are many other and better [staticsitegenerators](http://staticsitegenerators.net), but I searched for this features and did not found:

* edit without texteditor or ssh access. Using __webedit__ on your browser and get a textarea to change and create pages.
* __meta information__ for rendering (template, menue etc) or html metatags are at the __bottom__ of the page and only __optional__
* no indexed or automatic __menu__; just an extra included __page__ with a (nested) list.

Some more small features like titletags from the first heading, etc.


## TODO

- [ ] CSS-Mini-Reset realy needed
- prevent php etc upload!
- [ ] minfy als webservice?
- demo
- lib: https://github.com/lepture/editor or https://stackedit.io/
- const public
- https://github.com/blueimp/jQuery-File-Upload
- http://php.net/manual/de/function.realpath.php PHP 5.3
- source config without passwords etc
- _drf/config.yml to js serversite https://github.com/coolaj86/yamltojson.com
- [ ] URL builing? root dir : absolute relative only one dir
- [ ]* directory to namespace
- [ ]* directory doubled
- [ ]* global filenamepath
- [ ] test if yml exists from func
- [ ] bloggish posttemplate (date, archivcat) for webedit
- [ ] lasttest
- [ ] doku images timestamp

### 2.0
- source versioning
  - http://stackoverflow.com/questions/7447472/how-could-i-display-the-current-git-branch-name-at-the-top-of-the-page-of-my-de
- tags
- include http://www.mediawiki.org/wiki/Transclusion; Mustache ?
    - new article field (and include)
- listings
    - more cutter
- [ ]* index: dir as link include (for pix)

### Nice2Have
- trailing slash
- [ ] etherpad
- redirects with page

### Refactor

- [ ] 404 er save
- [ ] lib https://github.com/michelf/php-markdown extra (needs php 5.3)
