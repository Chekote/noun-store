[![Packagist Latest Stable Version](https://poser.pugx.org/chekote/noun-store/version.svg)](https://packagist.org/packages/chekote/noun-store)
[![Packagist Latest Unstable Version](https://poser.pugx.org/chekote/noun-store/v/unstable.svg)](https://packagist.org/packages/chekote/noun-store)
[![Packagist Total Downloads](https://poser.pugx.org/chekote/noun-store/downloads.svg)](https://packagist.org/packages/chekote/noun-store)
[![CircleCI](https://circleci.com/gh/Chekote/noun-store.svg?style=shield)](https://circleci.com/gh/Chekote/noun-store)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Chekote/noun-store/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Chekote/noun-store/?branch=master)
[![StyleCI](https://styleci.io/repos/63828286/shield?style=plastic)](https://styleci.io/repos/63828286)

# noun-store

Store and work with Nouns.

## Usage

todo

## Development

### Installing Development Pre-Requisites

Install [Docker](https://www.docker.com).

You will also want to ensure that `./bin` is in your `$PATH` and is the highest priority. You can do so by adding the
following to your shell profile:

```bash
export PATH=./bin:$PATH
```

### Installing The Project for Development

Clone the repository:

```bash
git clone git@github.com:Chekote/noun-store.git
cd noun-store
```

Build the Docker images:

```bash
docker/build_images.sh
```

### Executing tests

Tests are written using [phpunit](https://phpunit.de/). You can execute them via the command line:

```bash
phpunit
```
