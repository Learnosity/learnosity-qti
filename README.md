# Learnosity QTI

The learnosity-qti package converts QTI 2.1 Assessment Items to Learnosity Item and Question JSON.

This should run on PHP 5.5+


## Installation via Composer

Using Composer is the recommended way to install Learnosity QTI for PHP. In order to use the package with Composer,
you must add "learnosity/learnosity-qti" as a dependency in your project's composer.json file.

```
{
    "require": {
        "learnosity/learnosity-qti": "0.*"
    }
}
```

Then, install your dependencies

```
composer install
```

Or just do:

```
composer global require "learnosity/learnosity-qti"
```

Make sure to add $HOME/.composer/vendor/bin directory to your $PATH so the `mo` executable can be located by your system. If not, simple replace all `mo` commands below with `./bin/mo` (from the root of the project).

## Usage

### The `mo` command line runner

Use the command line tool, `mo` to run conversion jobs. To see all jobs, run `mo list`:

```
$ mo list

Usage:
  mo [command] [options] [--help]

Flags:
  --help                    Print the applications help

Commands:
  convert:to:learnosity     Converts QTI v2.1 to Learnosity JSON
  convert:to:qti            Converts Learnosity JSON to QTI v2.1

  list                      Lists all commands available
```

### Converting QTI to Learnosity JSON

To convert QTI 2.1 to Learnosity JSON, run the following:

```
mo convert:to:learnosity
```

By default this will look for content packages inside the `./data/input` directory, and output results (and log files) to `./data/output`. If you want to use different input and/or output paths you can use options:

```
mo convert:to:learnosity -i /my/path/to/qti -o /my/path/to/output/folder
```

Remember you can ask for `help`:

```
$ mo convert:to:learnosity --help

Usage:
  convert:to:learnosity [options]

Options:
  -i, --input=INPUT     The input path to your QTI content [default: "./data/input"]
  -o, --output=OUTPUT   An output path where the Learnosity JSON will be saved [default: "./data/output"]
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Converts QTI v2.1 to Learnosity JSON, expects to run on folder(s) with a imsmanifest.xml file
```


### Converting Learnosity JSON to QTI

To convert Learnosity JSON to QTI 2.1, run the following:

```
mo convert:to:qti
```

By default this will look for content packages inside the `./data/input` directory, and output results (and log files) to `./data/output`. If you want to use different input and/or output paths you can use options:

```
mo convert:to:qti -i /my/path/to/json -o /my/path/to/output/folder
```

Remember you can ask for `help`:

```
$ mo convert:to:qti --help

Usage:
  convert:to:qti [options]

Options:
  -i, --input=INPUT     The input path to your Learnosity content [default: "./data/input"]
  -o, --output=OUTPUT   An output path where the QTI will be saved [default: "./data/output"]
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Converts Learnosity JSON to QTI v2.1
```

Only supports Question JSON today.
