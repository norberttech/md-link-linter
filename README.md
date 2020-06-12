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

```bash
./mdlinklint --help

Usage:
  run [options] [--] [<path>]

Arguments:
  path                     Path in which md link linter should validate all markdown files

Options:
      --dry-run            Scan path and output md files
      --exclude[=EXCLUDE]  Exclude folders with this name (multiple values allowed)
      --mention[=MENTION]  Mentions whitelist (can include all team members or groups), if empty mentions are not validated (multiple values allowed)
  -h, --help               Display this help message
  -q, --quiet              Do not output any message
  -V, --version            Display this application version
      --ansi               Force ANSI output
      --no-ansi            Disable ANSI output
  -n, --no-interaction     Do not ask any interactive question
  -v|vv|vvv, --verbose     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

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
