php-site-maker
=====

A simple static site generator powered by PHP & Markdown. Based on [github.com/lonescript/php-site-maker](http://github.com/lonescript/php-site-maker)

## Format

Write files in [markdown](http://michelf.com/projects/php-markdown/).

Metainformation (template, comments, meta-description, title etc) can be defined in [yaml](http://www.yaml.org/spec/1.2/spec.html)
* for __each page__ at the bottom of sourcefile after the `#!` [Shebang](https://en.wikipedia.org/wiki/Shebang_%28Unix%29), can be redefined in config 'ymlseparator'.
* or for each __directory__ in *config.yml* 
* or in the __site__-config *_make/config.yml* 

## Write

Edit your website in different ways

* *normal* edit source files on filesystem and with your favourite text-editor. After editing call make.php in you CLI
* __webedit__ with the edit button on the page. After 'save' the single page will be created.
* receive changes via __git__ (or other __DCVS__) and update the source and call make.php manually or with a hook.

## Admin

There is no extra admininterface, edit existing pages with the edit button or create new with calling the page you want. More on [admin.md](blob/master/_source/admin.md)




## Setup

The public site needs only html and js files. Only *_make/*-directory is needed for updates. The *writer.php* has **no role or user validation**, Protect this against violaton:

* .htaccess for the whole *_make/*-directory. (Change existing .htaccess to a suitable [Basic access authentication](http://en.wikipedia.org/wiki/Basic_access_authentication))
* use editing only on secure machines like your desktop or intranet and publish all without *_make/* (e.g. `rsync --exclude=_make/`)


There is no __automatic URL handling__, config URL manually in the main *config.yml* and *.htaccess*.

## Directories

_make/
: all server sourcecode to create html files from *_source/* 'compiled' with all *_template/*

_source/
: content source written in Markdown

_template/
: some html skeletons wrapping in persisting areas like *header.php*, *footer.php* and *sidebar.php*. 

theme/
: clientsite assets like css and js.

## config.yml

### page

For every single page

layout
: template

name
: name for index or title

comment
: allow comments

index
: 1


### site

For the whole site

baseurl
: root url *http://example.com* or *http://example.com/mypage* for absolute URL inside the html.

sourcedir
: content source

htmldir
: directory for generated html-output


### Usage

```
edit `config.yml` first.

<!--more--> is avaliable
```
## TODO

* custom css and js
* cleanup css
* 404 if admin create
* file_put_contents notebook ??
* demo
* trailing slash
* php in webroot execute 
* libs
** https://github.com/michelf/php-markdown extra
** https://github.com/mustangostang/spyc/
** http://epiceditor.com/
** https://github.com/dhg/Skeleton/tree/master/stylesheets
* minfy
* after pagedurabel creat with all pages
* js var psmpage
* URL builing? root dir : absolute relative only one dir
* directory to namespace
** directory doubled
* test if yml exists from func
* bloggish posttemplate (date, archivcat) for webedit
* global filenamepath
* normalize page and blog
* include
* index: dir as link include (for pix)
* tags
* lasttest


### Refactor
* 404 er save
