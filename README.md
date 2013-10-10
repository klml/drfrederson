php-site-maker
=====

A simple static site generator powered by PHP & Markdown. Based on [github.com/lonescript/php-site-maker](http://github.com/lonescript/php-site-maker)


There are many other and better [ssg](https://github.com/pinceladasdaweb/Static-Site-Generators), but I searched for this fetaures I did not found so far:

* not-ITcrowds have no favourite texteditor or ssh access. Just hit __webeditbutton__ on your browser and get an textarea to change contents.
* no indexed, automatic __menu__; just an extra textblob. This is not so IT-sophisticated, but easy to understand. 

Some more small features like titletags from the first heading, etc


## Write

Write files in [markdown extra](http://michelf.ca/projects/php-markdown/extra/).

Edit your website in different ways:

* __normal__ edit source files on filesystem and with your favourite text-editor. After editing call make.php in you CLI
* __webedit__ with the edit button on the page. After 'save' the single page will be created.
* receive changes via __git__ (or other __DCVS__) and update the source and call make.php manually or with a hook.

There is no extra admininterface, edit existing pages with the edit button or create new with calling the page you want. More on [admin.md](_source/admin.md)

## Setup

Clone, create config from .example and create first version of site.

```
git clone git@github.com:klml/php-site-maker.git
cd php-site-maker/_make/
cp config.yml.example config.yml
php make.php
```

Used libraries are included as [submodules](http://git-scm.com/book/en/Git-Tools-Submodules).


```
git submodule init
git submodule update 
```


The public site, where ever it is located (other server, CDN, etc), needs only html and js files. Only *_make/*-directory is needed for updates. The *writer.php* has **no role or user validation**, Protect this against violaton:

* .htaccess for the whole *_make/*-directory. (Change existing .htaccess to a suitable [Basic access authentication](http://en.wikipedia.org/wiki/Basic_access_authentication))
* use editing only on secure machines like your desktop or intranet and publish all without *_make/* (e.g. `rsync --exclude=_make/`)


There is no __automatic URL handling__, config URL manually in the main *config.yml* and *.htaccess*.

## Directories

'_make/'
: all server sourcecode to create html files from *_source/* 'compiled' with all *_template/*

_source/
: content source written in Markdown

_template/
: some html skeletons wrapping in persisting areas like *header.php*, *footer.php* and *sidebar.php*. 

lib/
: clientsite used libraries (css, js).

## config.yml

Metainformation (template, comments, meta-description, title etc) can be defined in [yaml](http://www.yaml.org/spec/1.2/spec.html)
* for __each page__ at the bottom of sourcefile after the `#meta#`, can be redefined in config 'ymlseparator'.
* or for each __directory__ in *config.yml* 
* or in the __site__-config *_make/config.yml* 

### site wide

site-wide configurations, only usable in `_make/config.yml`.

siteName
: used after  

baseurl
: root url *http://example.com* or *http://example.com/mypage* for absolute URL inside the html. Works also with relative (leave empty).

disqusId
: if using [disqus.com](http://disqus.com/) for comments

htmlextension
: filename extension for html output. Mostly '.html' or nothing.

sourceextension
: filename extension for text sourcefiles

ymlseparator
: tag in pages to seperate content and config (`#meta#`).

path
: filepath for templates, text-source and target for html.

### page specific

Are used for every single page, but can get overwritten in directory or _make/config.yml.

layout
: template

title
: [HTML Title Element](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/title)

comment
: allow comments

pagedurable
: parts of the page always appear. 

description
: Some general site wide description




## TODO


- [ ] file_put_contents notebook ??
- [ ] include
- [ ]* index: dir as link include (for pix)
- [ ] demo
- [ ] trailing slash
- [ ] lib: https://github.com/michelf/php-markdown extra (needs php 5.3)
- [ ] lib: https://github.com/lepture/editor
- [ ] minfy
- [ ] after pagedurabel creat with all pages
- [ ] js var psmpage
- [ ] URL builing? root dir : absolute relative only one dir
- [ ]* directory to namespace
- [ ]* directory doubled
- [ ]* global filenamepath
- [ ] test if yml exists from func
- [ ] bloggish posttemplate (date, archivcat) for webedit
- [ ] tags
- [ ] lasttest



### Refactor
- [] 404 er save
