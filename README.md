php-site-maker
=====

A simple static site generator powered by PHP & Markdown

Usage:

```
edit `config.yml` first.

$ php new.php post my-post-title
    // Build a markdown file in `_post/my-post-title.md`.
    // `make.php` will build a html file in `./blog/year/month/my-post-title.html`

$ php new.php page page-name
    // Build a markdown file in `_page/page-name.md`.
    // `make.php` will build a html file in `./page-name/index.html`.

$ php make.php
    // Build html files. Generate your static site.

<!--more--> is avaliable
```

Online demo: [http://lonescript.github.io/php-site-maker](http://lonescript.github.io/php-site-maker)
