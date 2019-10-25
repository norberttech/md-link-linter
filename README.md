# Markdown Link Linter

Simple command line tool that aims to detect invalid links in
markdown files.  

Currently following types of links are being validated:

* relative links
* anchor links

```console
bin/mdlinklint path --exclude=vendor --exclude=node_modules
```