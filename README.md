php-site-maker
=====

A simple static site generator powered by PHP & Markdown. Based on [github.com/lonescript/php-site-maker](http://github.com/lonescript/php-site-maker)

## Format

Write files in [markdown](http://michelf.com/projects/php-markdown/).

Metainformation can be defined for each page at the bottom after `---`

## Directories

_make/
: all server sourcecode to create html files from ''_source/'' 'compiled' with all ''_template/''

_source/
: content source written in Markdown

_template/
: some html skeletons wrapping in persisting areas like ''header.php'', ''footer.php'' and ''sidebar.php''. 

theme/
: clientsite assets like css and js.

## config.yml

''config.yml''

baseurl
: root url ''http://example.com'' or ''http://example.com/mypage'' for absolute URL inside the html.

sourcedir
: content source

htmldir
: directory for generated html-output


### Usage

```
edit `config.yml` first.

$ php new.php page page-name
    // Build a markdown file in `_page/page-name.md`.
    // `make.php` will build a html file in `./page-name/index.html`.

$ php make.php
    // Build html files. Generate your static site.

<!--more--> is avaliable
```
## TODO

* call single page
* test if yml exists from func
* config.yml to source? prevent if config area
* config.yml per dir
* web make
* webeditor
  * bloggish posttemplate (date, archivcat) for webedit
* normalize page and blog
* include
* index: dir as link include (for pix)
