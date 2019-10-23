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
        "learnosity/learnosity-qti": "2.*"
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

For bleeding edge:
```
{
    "require": {
        "learnosity/learnosity-qti": "dev-develop"
    }
}
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

By default, Learnosity QTI expects a QTI content package, including an imsmanifest.xml.

To convert QTI 2.1 to Learnosity JSON, run the following:

```
mo convert:to:learnosity --organisation_id [integer]
```

`organisation_id` is a mandatory option, where the value is an integer of your Learnosity item bank (organisation).

By default this will look for content packages inside the `./data/input` directory, and output raw results to `./data/output/raw` and final item JSON to `./data/output/final`. A job manifest file will be written to `./data/output/log`, indicating errors or warnings found during the conversion.

Note that only the `data` folder is present in this repository, you can create the `data/input` folder to add content packages there. The `data/output` path will be created automatically if you don't override via input options.

### Conversion options
If you want to use different input and/or output paths you can use options:

```
mo convert:to:learnosity --input /my/path/to/qti --output /my/path/to/output/folder --organisation_id [integer]
```

All supported input options are as follows:

| Option  | Default | Description |
|---|---|---|
| &#x2011;&#x2011;organisation_id  | | [Mandatory] Which Learnosity item bank to use, contact Learnosity for your `organisation_id` value |
| --input  | `./data/input` | File system path to the source content being converted |
| --output  | `./data/output` | File system path to where the converted content will be written |
| &#x2011;&#x2011;item-reference-source  | `item` | Where to retrieve each items unique identifier from the QTI.<br><dl><dt>item</dt><dd> uses the identifier attribute on the `<assessmentItem>` element</dd><dt>metadata</dt><dd>uses the `<identifier>` element from the LOM metadata in the manifest, if available. If no `<identifier>` is found, then this parameter operates in "item" mode</dd><dt>filename</dt><dd>uses the identifier attribute on the `<resource>` element in the manifest</dd><dt>resource</dt><dd>uses the basename of the `<assessmentItem>` XML file</dd></dl> |
| --passage-only-items  | `No` | Whether HTML passages should be created as separate, passage-only, items. <br><dl><dt>No</dt><dd>No separate items will be created</dd><dt>Yes</dt><dd> Separate items containing only passages will be created</dd> |
| --single-item  | `No` | To convert a single QTI `<assessmentItem>` instead of a full content package, pass `Yes` and a path to a single XML file to `--input` |

## Assets
The conversion library will update any asset URL inside QTI content to use the fololwing Learnosity CDN address:
`https://assets.learnosity.com/organisations/[integer]/[filename]`

The `organisation` value is taken from the `--organisation_id` input parameter passed to the command line.

Supported file types include:
* images
* audio files (mp3)
* video files (mp4)

When importing content using the Data API, you can add files using the [Upload Assets](https://reference.learnosity.com/data-api/endpoints/itembank_endpoints#uploadAssets) endpoint.

## Metadata (LOM)
Metadata will be taken from the content package manifest and converted to [Learnosity tags](https://help.learnosity.com/hc/en-us/articles/360000758597-Understanding-Tag-Formats-for-Content-Creation-and-Filtering). The format is assumed to be:

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

## Supported Interactions - QTI to Learnosity
The following QTI v2.1 interactions are supported:

| QTI Interaction | Learnosity Question Type | Learnosity Widget Value |
|---|---|--|
|ChoiceInteraction|	Multiple Choice Question| mcq |
|ExtendedTextInteraction|	Essay| longtextV2 |
|GraphicGapMatchInteraction|	Image Association| imageclozeassociationV2 |
|GapMatchInteraction|	Cloze Association| clozeassociation |
|HottextInteraction|	Token Highlight| tokenhighlight |
|InlineChoiceInteraction|	Cloze Dropdown| clozedropdown |
|MatchInteraction|	Choice Matrix| choicematrix |
|OrderInteraction|	Order List| orderlist |
|TextEntryInteraction|	Cloze Text| clozetext |
|HotspotInteraction|	Hotspot| hotspot |

## Rubrics
`<rubricBlock>` elements are commonplace in QTI documents. Learnosity can treat these as:
* passages
* distractor rationale
* rating question types (for scoring)

## Passages
Learnosity has the concept of a [Passage](https://authorguide.learnosity.com/hc/en-us/articles/360000445597-Shared-Passages), which is a separate HTML fragment that can be added to single items, or shared across multiple items. By default, the conversion library looks for the following QTI to be converted into a passage:

```
<rubricBlock use="context" view="candidate author proctor scorer testConstructor tutor">
    <div>
        <object data="../passages/passage.html" type="text/html" />
    </div>
</rubricBlock>
```

In the example above, the contents of the passage come from an external file in the content package. You can also include the passage contents inline (inside the `<rubricBlock>` element).

Note that the `use` attribute must be `context`, and the `view` attribute must include `candidate`.

The Learnosity JSON generated would contain 2-columns, the passage(s) in the left and the question(s) in the right.

### Multiple passages
If 2 passages are found in a QTI item, a tabbed interface will appear in the converted JSON (in the left-column).

If 3 (or more) passages are found, they will be stacked vertically in the UI (no tabs).

### "Shared" Passages
In the converted results, the Learnosity reference (unique identifier) to a passage is generated from a hash of the passage body. So, we automatically "share" a passage if an exact match is found based on the contents of the passage.

## Distractor rationale
### Students
If the `use` attribute of a `<rubricBlock>` element is `rationale`, and the `view` attribute contains `candidate`, the conversion library will generate [`distractor_rationale_response_level`](https://authorguide.learnosity.com/hc/en-us/articles/360000448738-Understanding-the-Extras-Section-of-the-Question-Editor) inside the question metadata.

The contents of the `<rubricBlock>` will be broken down by block elements, one for each array element of `distractor_rationale_response_level`. Eg:

```
<rubricBlock use="rationale" view="candidate">
    <p><span">Distractor:</span> Natoque velit etiam sem varius consequat.</p>
    <p><span">Distractor:</span> Enim vestibulum habitant dui ut morbi.</p>
    <p><span">Correct: </span>Dapibus scelerisque diam lacus nec lacus.</p>
    <p><span">Distractor:</span> Himenaeos fringilla arcu suspendisse pulvinar.</p>
</rubricBlock>
```

Would generate the following JSON:

```
{
    "metadata": {
        "distractor_rationale_response_level": [
            "<p><span>Distractor:</span> Natoque velit etiam sem varius consequat.</p>",
            "<p><span>Distractor:</span> Enim vestibulum habitant dui ut morbi.</p>",
            "<p><span>Correct: </span>Dapibus scelerisque diam lacus nec lacus.</p>",
            "<p><span>Distractor:</span> Himenaeos fringilla arcu suspendisse pulvinar.</p>"
        ]
    }
}
```

If `<feedbackInline>` elements are found, they will be converted to `distractor_rationale_response_level`. Eg with the following for a choice interaction:

```
<choiceInteraction responseIdentifier="RESPONSE" maxChoices="1" shuffle="false">
    <prompt>
        [Question prompt here]
    </prompt>
    <simpleChoice identifier="A">
        <feedbackInline identifier="feedback6399842" outcomeIdentifier="FEEDBACK" showHide="show">
            <object data="71de4a8f-b3c6-4518-99dd-668774d507de.html" type="text/html" />
        </feedbackInline>
        <object data="25d4c6ed-ae48-4bc1-b0b7-550616080534.html" type="text/html" />
    </simpleChoice>
    <simpleChoice identifier="B">
        <object data="72e8b863-03d3-4c1c-84bd-1d944c563fd2.html" type="text/html" />
    </simpleChoice>
    <simpleChoice identifier="C">
        <feedbackInline identifier="feedback6399844" outcomeIdentifier="FEEDBACK" showHide="show">
            <object data="37665b88-75fa-45a9-bf1d-a1bd675c7657.html" type="text/html" />
        </feedbackInline>
        <object data="a819c432-90f0-47c8-a13b-d341582bf86d.html" type="text/html" />
    </simpleChoice>
    <simpleChoice identifier="D">
        <feedbackInline identifier="feedback6399845" outcomeIdentifier="FEEDBACK" showHide="show">
            <object data="c5b5531d-bb61-4965-82a9-189edfaa15cb.html" type="text/html" />
        </feedbackInline>
        <object data="80fff1a9-1351-46f5-b69b-ab817dd95d58.html" type="text/html" />
    </simpleChoice>
</choiceInteraction>
```

The `feedbackInline` contents will be converted to `distractor_rationale_response_level` array elements.

### Graders
If the `class` attribute of a `<rubricBlock>` element is `DistractorRationale`, and the `view` attribute contains `author`, the conversion library will generate `distractor_rationale_scorer` as a custom metadata field inside the question metadata.

The contents of the `<rubricBlock>` will be used, eg:

```
<rubricBlock class="DistractorRationale" view="author">
    <p>Parturient est morbi suspendisse nisi a duis scelerisque integer ut...</p>
</rubricBlock>
```

Would generate the following JSON:

```
{
    "metadata": {
        "distractor_rationale_scorer": "<p>Parturient est morbi suspendisse nisi a duis scelerisque integer ut...</p>"
    }
}
```

It would up to the host page calling the Assessment API to render this content to a grader.


## Unsupported
Learnosity QTI does not support:
* `<assessmentItems>` with no interactions (passage-only or rubric-only)
* Custom CSS stylesheets. These must be loaded separately at run time for the host page initialising the Assessment API.

### Help
Remember you can ask for `help`:

```
$ mo convert:to:learnosity --help

Usage:
  convert:to:learnosity [options]

Options:
  -i, --input=INPUT                                    The input path to your QTI content [default: "./data/input"]
  -o, --output=OUTPUT                                  An output path where the Learnosity JSON will be saved [default: "./data/output"]
      --organisation_id=ORGANISATION_ID                The identifier of the item bank you want to import content into [default: ""]
      --item-reference-source[=ITEM-REFERENCE-SOURCE]  The source to use to extract the reference for the item. Valid values are the following:
                                                       item     - uses the identifier attribute on the <assessmentItem> element
                                                       metadata - uses the <identifier> element from the LOM metadata in the manifest, if available. If
                                                       no <identifier> is found, then this parameter operates in "item" mode
                                                       resource - uses the identifier attribute on the <resource> element in the manifest
                                                       filename - uses the basename of the <assessmentItem> XML file
                                                       [default: "metadata"]
      --passage-only-items[=PASSAGE-ONLY-ITEMS]        If you pass the value as "Y", the conversion library will convert regular assessment items as well
                                                       as passage-only items, if defined in the manifest [default: "N"]
      --single-item[=SINGLE-ITEM]                      If you pass the value as "Y", the conversion library will convert only single xml file [default: "N"]
  -h, --help                                           Display this help message
  -q, --quiet                                          Do not output any message
  -V, --version                                        Display this application version
      --ansi                                           Force ANSI output
      --no-ansi                                        Disable ANSI output
  -n, --no-interaction                                 Do not ask any interactive question
  -v|vv|vvv, --verbose                                 Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Converts QTI v2.1 to Learnosity JSON, expects to run on folder(s) with a imsmanifest.xml file
```

# Importing into Learnosity
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

By default this will look for content packages inside the `./data/input` directory, and output raw results to `./data/output/raw`. A manifest file will be written to `./data/output/log`.

Note that only the `data` folder is present in this repository, you can create the `data/input` folder to add content packages there. The `data/output` path will be created automatically if you don't override via input options.

### Conversion options
If you want to use different input and/or output paths you can use options:

```
mo convert:to:qti --input /my/path/to/learnosity-json --output /my/path/to/output/folder
```

All supported input options are as follows:

| Option  | Default | Description |
|---|---|---|
| --input  | `./data/input` | File system path to the source content being converted |
| &#x2011;&#x2011;output | `./data/output` | File system path to where the converted content will be written |
| &#x2011;&#x2011;format | `qti` | A flag to choose how to format the QTI output content package, from a list of supported formats. This option supports the following possible values: (canvas, qti). Pass the canvas option to export. QTI content that is compatible with Canvas LMS. The default is qti, which outputs non LMS-specific QTI. |

### Learnosity JSON format
This conversion tool expects to be given JSON in the format that is returned by the [offline package endpoint of the Data API](https://reference.learnosity.com/data-api/endpoints/itembank_endpoints#getOfflinePackage). The Data API `itembank/offlinepackage` endpoint returns Activities/Items/Questions/Features in a single directory. It also contains any assets, including images, audio or video, that are part of the content.

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

| Learnosity Question Type | Learnosity Widget Value | QTI Interaction |
|---|---|---|
|Choice Matrix| choicematrix |MatchInteraction|
|Cloze Association| clozeassociation |GapMatchInteraction|
|Cloze Dropdown| clozedropdown |InlineChoiceInteraction|
|Cloze Text| clozetext |TextEntryInteraction|
|Essay| longtext |ExtendedTextInteraction|
|Essay with rich text| longtextV2 |ExtendedTextInteraction|
|Hotspot| hotspot |HotspotInteraction|
|Image Cloze Association| imageclozeassociation |GraphicGapMatchInteraction|
|Image Cloze Association V2| imageclozeassociationV2 |GraphicGapMatchInteraction|
|Multiple Choice Question| mcq |ChoiceInteraction|
|Order List| orderlist |OrderInteraction|
|Passage| sharedpassage |N/A|
|Plain Text| plaintext |ExtendedTextInteraction|
|Short Text| shorttext |TextEntryInteraction|
|Token Highlight| tokenhighlight |HottextInteraction|

### Help

Remember you can ask for `help`:

```
$ mo convert:to:qti --help

Usage:
  convert:to:qti [options]

Options:
  -i, --input=INPUT     The input path to your Learnosity content [default: "./data/input"]
  -o, --output=OUTPUT   An output path where the QTI will be saved [default: "./data/output"]
  -f, --format=FORMAT   A flag to choose how to format the QTI output content package, from a list of supported formats.
                        This option supports the following possible values: (canvas, qti). Pass the canvas option to export
                        QTI content that is compatible with Canvas LMS. The default is qti, which outputs non LMS-specific QTI.
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
