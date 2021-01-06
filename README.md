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
      --dry-run               Scan path and output md files
      --exclude=EXCLUDE       Exclude folders with this name (multiple values allowed)
      --mention=MENTION       Mentions whitelist (can include all team members or groups), if empty mentions are not validated (multiple values allowed)
  -h, --help                  Display help for the given command. When no command is given display help for the run command
  -q, --quiet                 Do not output any message
  -V, --version               Display this application version
      --ansi                  Force ANSI output
      --no-ansi               Disable ANSI output
  -n, --no-interaction        Do not ask any interactive question
  -bf, --break-on-failure     Break the inspection on first failure
  -rp, --root-path=ROOT-PATH  Root path used to assert absolute links. Link: [link](/nested/file.php) will check if file /nested/file.php exists from this path
  -v|vv|vvv, --verbose        Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

![Preview](/docs/preview.png)

### Validating mentions 

Using `--mention` option you can set allowed mentions 

```console
bin/mdlinklint path --mentions=norberttech --mention=team_name
```

If not used, mention link assertion will always pass.

## Installation

### Docker

**Recommended** - md-link-linter is not a type of tool you want to put into your project as a dependency because it comes 
with it own dependencies that might overlaps with yours.

[Docker Hub - norberttech/md-link-linter](https://hub.docker.com/r/norberttech/md-link-linter)

### Composer

```console
composer global require norberttech/md-link-linter
```

### Phive

Since md-link-linter relay on `\realpath` function which does not work in Phar environment (explanation below)
there are no plans for now to make it available through phive. 

> The function realpath() will not work for a file which is inside a Phar as such path would be a virtual path, not a real one.

[php.net realpath documentation](https://www.php.net/manual/en/function.realpath.php)

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
