drfrederson
=====

A simple static site generator powered by PHP, YAML, Markdown and mustache.

There are many other and better [staticsitegenerators](http://staticsitegenerators.net), but I searched for these features and did not find:

* edit without texteditor or ssh access. Using __webedit__ on your browser and get a textarea to change and create pages.
* __meta information__ for rendering (template, menue etc) or html metatags are at the __bottom__ of the page and only __optional__
* no indexed or automatic __menu__; just an extra included __page__ with a (nested) list.

Some more small features like titletags from the first heading, etc.

All about editing, admin, setup and templates in the [wiki](https://github.com/klml/drfrederson/wiki/drfrederson).


[Demo](http://drf.grus.uberspace.de/drf:admin) (use user `a` and password `a`. There is __no__ further protection, so all changes will be deleted sporadic for cleanup.)


## License
The MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


## TODO

- version source
- meta to client, need for
  - for new pages (drf-write.jquery.js)
    - sourcepath (from config.yml)
    - prefill
    - source extension for new
  - metaseperator for live preview without meta
  - area, for edit link ?
  - template ?
  - use https://github.com/coolaj86/yamltojson.com, dont know 
- Hooks
- template fallback
- [ ] test if yml exists from func
- _drf/config.yml to js serversite https://github.com/coolaj86/yamltojson.com
- live preview without meta
- lib: https://github.com/lepture/editor or https://stackedit.io/
- const public
- https://github.com/blueimp/jQuery-File-Upload
- http://php.net/manual/de/function.realpath.php PHP 5.3
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
- [ ] minfy als webservice?
- trailing slash
- [ ] etherpad
- redirects with page

### Refactor

- [ ] 404 er save
- [ ] lib https://github.com/michelf/php-markdown extra (needs php 5.3)
