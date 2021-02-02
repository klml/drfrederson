drfrederson
=====

A simple static site generator powered by PHP, YAML, [markdown extra](https://michelf.ca/projects/php-markdown/extra/) and mustache. Licensed under [MIT License](LICENSE.md)

## features

There are many other and better [staticsitegenerators](http://staticsitegenerators.net), but I missed these:


### writesource.php

* edit without texteditor or ssh access. Using __webedit__ on your browser and get a textarea or [markdown-WYSIWM](https://simplemde.com/) to change and create pages.

### make.php

* __meta information__ for rendering (template, menue etc) or html metatags are at the __bottom__ of the page and only __optional__ (tried to use my own standard [PROSErial](https://github.com/klml/PROSErial)).
* use source directories as __namespace__, with customizing namespaceseperators (```namespace:pagetitle```).
* include sourcefiles as __areas__ in templates (for menus, sidebars, trackingpixels).
* if there is no ```<title>``` set in meta, drfrederson uses the first heading as pagetitle.
* wikistyle internal links (```[[MyPage]]```).
* txt-files get rendered with newlines as breaks (```<br>```).
* render markdown to html pages and json.


All about editing, admin, setup and templates in the [wiki](https://github.com/klml/drfrederson/wiki/drfrederson).


## drf users 
Sites made with drfrederson:

* [schuhmacherei-allinger.de](http://schuhmacherei-allinger.de)
* [sailer-grafik-design.de](http://sailer-grafik-design.de)


## similar projects 

* [Hacker CMS](//github.com/kentonv/ssjekyll/) has a webinterface. The filetree, is a feature, drfrederson is missing at all. But it uses the common ssg jekyll, so users have to deal with frontmatter and not [PROSErial](//github.com/klml/PROSErial).
* [prose.io](https://prose.io) is a writing orientated webinterface for github



## TODO


- version source etc
    - https://github.com/blueimp/jQuery-File-Upload
    - template fallback or in init
- meta to client, need for
  - for new pages (drf-write.jquery.js)
    - sourcepath (from config.yml)
    - prefill
    - source extension for new
  - metaseperator for live preview without meta
  - area, for edit link ?
  - template ?
  - secure passwords for upload etc, extra config.secure.yml ?
- Hooks
- plugins
  - Drf automatic ID for headimgs
- live preview without meta
- const public
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
    - [More Tag](https://en.support.wordpress.com/more-tag/)
- [ ] index: dir as link include (for pix)

### Nice2Have
- [ ] minfy als webservice?
- trailing slash
- [ ] etherpad
- redirects with page

### Refactor

- [ ] 404 er save
- [ ] lib https://github.com/michelf/php-markdown extra (needs php 5.3)
