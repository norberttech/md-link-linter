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


## Installation

```console
composer require --dev norzechowicz/md-link-linter
```

### Validating mentions 

Using `--mention` option you can set allowed mentions 

```console
bin/mdlinklint path --mentions=norzechowicz --mention=team_name
```

If not used, mention link assertion will always pass.
