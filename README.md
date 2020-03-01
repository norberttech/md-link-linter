# Markdown Link Linter

Simple command line tool that aims to detect invalid links in
markdown files.  

Currently following types of links are being validated:

* relative links
* anchor links
* mention links

```console
bin/mdlinklint path --exclude=vendor --exclude=node_modules
```

First argument `path` can be skipped or overwritten by environment variable `MD_LINTER_SCAN_DIR` 

```console
MD_LINTER_SCAN_DIR=path bin/mdlinklint --exclude=vendor --exclude=node_modules
```

When both, argument path and environment variable are present, environment variable takes priority. 

![Preview](/docs/preview.jpg)

### Validating mentions 

Using `--mention` option you can set allowed mentions 

```console
bin/mdlinklint path --mentions=norberttech --mention=team_name
```

## Installation

### Composer

```console
composer require --dev norberttech/md-link-linter
```

### Docker

[Docker Hub - norberttech/md-link-linter](https://hub.docker.com/r/norberttech/md-link-linter)

If not used, mention link assertion will always pass.

## Development

### Install dependencies

```console
composer install
```

### Run tests

```console
composer tests
```

### Codding standards

This command might change your code!

```console
composer cs:php:fix
```
