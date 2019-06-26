# Learnosity QTI

This package converts between QTI 2.1 Assessment Items and Learnosity JSON.

You can choose between 2 main jobs:

* `convert:to:learnosity` - Converts QTI v2.1 to Learnosity JSON
* `convert:to:qti` - Converts Learnosity JSON to QTI v2.1


## Installation via Composer

Using Composer is the recommended way to install Learnosity QTI for PHP. In order to use the package with Composer,
you must add "learnosity/learnosity-qti" as a dependency in your project's composer.json file.

```
{
    "require": {
        "learnosity/learnosity-qti": "1.*"
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

Make sure to add $HOME/.composer/vendor/bin directory to your $PATH so the `mo` executable can be located by your system. If not, simply replace all `mo` commands below with `./bin/mo` (from the root of the project).

This package has been tested on PHP 7.1+

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

## Converting QTI to Learnosity JSON

You must provide a QTI content package including an imsmanifest.xml.

To convert QTI 2.1 to Learnosity JSON, run the following:

```
mo convert:to:learnosity
```

By default this will look for content packages inside the `./data/input` directory, and output raw results to `./data/output/raw` and final item JSON to `./data/output/final`. A manifest file will be written to `./data/output/log`.

Note that only the `data` folder is present in this repository, you can create the `data/input` folder to add content packages there. The `data/output` path will be created automatically if you don't override via input options.

### Conversion options
If you want to use different input and/or output paths you can use options:

```
mo convert:to:learnosity --input /my/path/to/qti --output /my/path/to/output/folder --organisation_id [integer]
```

All supported input options are as follows:

| Option  | Description |
|---|---|
| --input  | File system path to the source content being converted |
| --output  | File system path to where the converted content will be written |
| &#x2011;&#x2011;organisation_id  | Which Learnosity organisation to use for asset paths (contect Learnosity for your `organisation_id` value) |
| &#x2011;&#x2011;item-reference-source  | Where to retrieve each items unique identifier.<br><dl><dt>item</dt><dd>(default) uses the identifier attribute on the `<assessmentItem>` element</dd><dt>metadata</dt><dd>uses the `<identifier>` element from the LOM metadata in the manifest, if available. If no `<identifier>` is found, then this parameter operates in "item" mode</dd><dt>filename</dt><dd>uses the identifier attribute on the `<resource>` element in the manifest</dd><dt>resource</dt><dd>uses the basename of the `<assessmentItem>` XML file</dd></dl> |

### Metadata (LOM)
Metadata will be taken from the manifest and converted to Learnosity tags. The format is assumed to be:

```
<imsmd:lom>
    <imsmd:classification>
        <imsmd:taxonPath>
            <imsmd:source>
                <imsmd:string xml:lang="en">GradeLevel</imsmd:string>
            </imsmd:source>
            <imsmd:taxon>
                <imsmd:entry>
                    <imsmd:string xml:lang="en">6</imsmd:string>
                </imsmd:entry>
            </imsmd:taxon>
        </imsmd:taxonPath>
    </imsmd:classification>
</imsmd:lom>
```

This will be converted to the following Learnosity JSON (snippet only):

```
{
    "tags": {
        "GradeLevel": [
            "6"
        ]
    }
}
```

Note that `<imsmd:source>` translates to Learnosity tag types, and `<imsmd:taxon>` translates to tag names.

### Supported Interactions - QTI to Learnosity
The following QTI v2.1 interactions are supported:

| QTI Interaction | Learnosity Question Type |
|---|---|
|ChoiceInteraction|	Multiple Choice Question|
|ExtendedTextInteraction|	Long Text|
|GraphicGapMatchInteraction|	Image Association|
|GapMatchInteraction|	Cloze Association|
|HottextInteraction|	Token Highlight|
|InlineChoiceInteraction|	Cloze Dropdown|
|MatchInteraction|	Choice Matrix|
|OrderInteraction|	Order List|
|TextEntryInteraction|	Cloze Text|
|HotspotInteraction|	Hotspot|

### Help
Remember you can ask for `help`:

```
$ mo convert:to:learnosity --help

Usage:
  convert:to:learnosity [options]

Options:
  -i, --input=INPUT     The input path to your QTI content [default: "./data/input"]
  -o, --output=OUTPUT   An output path where the Learnosity JSON will be saved [default: "./data/output"]
      --organisation_id=ORGANISATION_ID  The identifier of the item bank you want to import content into [default: ""]
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

### Importing into Learnosity
Once you have Learnosity JSON (the `final` folder), you can use the Data API to import into your Learnosity hosted item bank.

Example of the output format is:

```
{
    "qtiitems": {
        "[item-reference]": {
            "item": {},
            "questions": [],
            "features": [],
            "manifest": [],
            "rubric": null,
            "assumptions": []
        }
    }
}
```

Loop over all item objects inside `qtiitems`.
Inside each item object, import the questions (setQuestions) and features (setFeatures) first, then the item (setItems). Setting items will automatically import any tags that were in the manifest.

 * [Import questions](https://docs.learnosity.com/analytics/data/endpoints/itembank_endpoints#setQuestions)
 * [Import features](https://docs.learnosity.com/analytics/data/endpoints/itembank_endpoints#setFeatures)
 * [Import items](https://docs.learnosity.com/analytics/data/endpoints/itembank_endpoints#setItems)


## Converting Learnosity JSON to QTI

To convert Learnosity JSON to QTI 2.1, run the following:

```
mo convert:to:qti
```

By default this will look for content packages inside the `./data/input` directory, and output raw results to `./data/output/raw` and final item JSON to `./data/output/final`. A manifest file will be written to `./data/output/log`.

Note that only the `data` folder is present in this repository, you can create the `data/input` folder to add content packages there. The `data/output` path will be created automatically if you don't override via input options.

### Conversion options
If you want to use different input and/or output paths you can use options:

```
mo convert:to:qti --input /my/path/to/learnosity-json --output /my/path/to/output/folder
```

All supported input options are as follows:

| Option  | Description |
|---|---|
| --input  | File system path to the source content being converted |
| --output  | File system path to where the converted content will be written |

### Learnosity JSON format
This conversion tool expects to be given JSON in the format that is returned by the [offline package endpoint of the Data API](https://reference.learnosity.com/data-api/endpoints/itembank_endpoints#getOfflinePackage). The Data API itembank/offlinepackage endpoint returns Activities/Items/Questions/Features in a single directory. It also contains any assets, including images, audio or video, that are part of the content.

The directory returned from the itembank/offlinepackage endpoint contains the following files:

```
Learnosity/
 	itembank/
		activities/
			hashedfilename.json
		assets/
			image1.jpg
			image2.jpg
		items/
			hashedfilename.json
			hashedfilename.json
			hashedfilename.json
```

Each JSON file within the items folder is named from a (lower case) MD5 hash of the item reference. The contents of each item file will be something like:

```
{
    "reference": "",
    "content": "",
    "workflow": null,
    "metadata": {
        "acknowledgements": null,
        "scoring_type": "per-question"
    },
    "tags": {},
    "questions": [],
    "features": []
}
```

### Supported Question Types - Learnosity to QTI
The following Learnosity question types are supported:

| Learnosity Question Type | QTI Interaction |
|---|---|
|Choice Matrix|MatchInteraction|
|Cloze Association|GapMatchInteraction|
|Cloze Dropdown|InlineChoiceInteraction|
|Cloze Text|TextEntryInteraction|
|Image Cloze Association|GraphicGapMatchInteraction|
|Image Cloze Association V2|GraphicGapMatchInteraction|
|Long Text|ExtendedTextInteraction|
|Long Text V2|ExtendedTextInteraction|
|Multiple Choice Question|ChoiceInteraction|
|Order List|OrderInteraction|
|Plain Text|ExtendedTextInteraction|
|Short Text|TextEntryInteraction|
|Token Highlight|HottextInteraction|
|Hotspot|HotspotInteraction|

### Help

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
