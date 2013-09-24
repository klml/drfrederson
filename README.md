php-site-maker
=====

A simple static site generator powered by PHP & Markdown. Based on [github.com/lonescript/php-site-maker](http://github.com/lonescript/php-site-maker)

## Format

Write files in [markdown](http://michelf.com/projects/php-markdown/).

Metainformation can be defined in [yaml](http://www.yaml.org/spec/1.2/spec.html)
* for each page at the bottom after `---`
* or for each directory in ''config.yml'' 
* or in the site-config ''_make/config.yml'' 

## Write

Edit your website in diffrent ways

* *normal* edit source files on filesystem and text-editor
* webedit with the edit button on the page
* receive changes via git (or other DCVS) and update the source

## Setup

The public site needs only html and js files. Only ''_make/''-Directory is needed for updates. Thw ''writer.php'' has **no role or user validation**, Protect this against violaton:

* htaccess with 
* use ist only on secure machines like Desktops Intranets and publish all without ''_make/'' (e.g. `rsync --exclude=_make/`)



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

* merge init create
* 404 er
* remove site title from config
* meta desc to yaml
* test if yml exists from func
* webeditor
  * bloggish posttemplate (date, archivcat) for webedit
  * create new page
  * if not loadable
* global filenamepath
* pagedurable by directory
* normalize page and blog
* include
* index: dir as link include (for pix)
* first H1 as titel
