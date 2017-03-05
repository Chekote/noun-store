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
cd noun=store
```

Build the Docker images:

```bash
docker/build_images.sh
```
