[![Packagist Latest Stable Version](https://img.shields.io/packagist/v/chekote/noun-store.svg)](https://packagist.org/packages/chekote/noun-store)
[![Packagist Latest Unstable Version](https://img.shields.io/packagist/vpre/chekote/noun-store.svg)](https://packagist.org/packages/chekote/noun-store)
[![Packagist Total Downloads](https://img.shields.io/packagist/dt/chekote/noun-store.svg)](https://packagist.org/packages/chekote/noun-store)
[![CircleCI](https://img.shields.io/circleci/project/github/Chekote/noun-store/master.svg)](https://circleci.com/gh/Chekote/noun-store)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/chekote/noun-store/2.0.svg)](https://scrutinizer-ci.com/g/Chekote/noun-store/?branch=2.0)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/chekote/noun-store/master.svg)](https://scrutinizer-ci.com/g/Chekote/noun-store/?branch=master)
[![StyleCI](https://styleci.io/repos/63828286/shield?style=plastic)](https://styleci.io/repos/63828286)

# noun-store

Store and work with Nouns.

## Usage

1. Add it to your requirements

```bash
composer require chekote/noun-store
```

### Make a store

```php
$store = new \Chekote\NounStore\Store();
```

### Store something

```php
$john = new Person();
$john->firstName = 'John';
$john->lastName = 'Smith';

$store->set('best friend', $john);
```

### Check if we have something

```php
$store->keyExists('best friend');
```

### Assert if we have something

```php
$store->assertKeyExists('best friend');
```

### Retrieve something

```php
$store->get('best friend');
```

### Store something else in the same key

```php
$chris = new Person();
$chris->firstName = 'Chris';
$chris->lastName = 'Pratt';

$store->set('best friend', $chris);
```

### Retrieve the new thing

```php
$store->get('best friend');

or

$store->get('2nd best friend');

or

$store->get('best friend', 1);
```

### Retrieve the old thing

```php
$store->get('1st best friend');

or

$store->get('best friend', 0);
```

### Empty the store

```php
$store->reset();
```

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

### Executing tests

Tests are written using [phpunit](https://phpunit.de/). You can execute them via the command line:

```bash
phpunit
```
