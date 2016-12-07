# Learnosity QTI

Include this package into easily convert QTI Assessment Item to our Item and Question JSON format
This SDK should run on PHP 5.5+

Live Demo: [http://docs.learnosity.com/authoring/qti/demo](http://docs.learnosity.com/authoring/qti/demo)

Documentation: [http://docs.learnosity.com/authoring/qti](http://docs.learnosity.com/authoring/qti)

## Installation via Composer

Using Composer is the recommended way to install the Learnosity SDK for PHP. In order to use the SDK with Composer,
you must add "learnosity/learnosity-qti" as a dependency in your project's composer.json file.

```
  {
    "require": {
        "learnosity/learnosity-qti": "x.x.x"
    }
  }
```

Then, install your dependencies 

```
composer install
```

## Usage

### Converting Learnosity JSON to QTI XML string

ie. `item.json`
```
{
    "type": "mcq",
    "widget_type": "response",
    "reference": "DEMO_1_2",
    "data": {
        "instant_feedback": true,
        "options": [
            {
                "value": "0",
                "label": "Tomato"
            },
            {
                "value": "1",
                "label": "Orange"
            },
            {
                "value": "2",
                "label": "Celery"
            },
            {
                "value": "3",
                "label": "Pear"
            }
        ],
        "stimulus": "Pick the odd one out",
        "type": "mcq",
        "ui_style": {
            "columns": 4,
            "type": "horizontal"
        },
        "validation": {
            "scoring_type": "exactMatch",
            "valid_response": {
                "score": 3,
                "value": [
                    "2"
                ]
            }
        }
    }
}
```
```
use LearnosityQti\Converter;

$question = json_decode(file_get_contents('item.json'), true);
list($xmlString, $manifest) = Converter::convertLearnosityToQtiItem($question);

var_dump($xmlString);
var_dump($manifest);
```

### Converting QTI XML string to Learnosity JSON

```
use LearnosityQti\Converter;

$xmlString = '<assessmentItem xsi:schemaLocation="http://www.imsglobal.org/xsd/imsqti_v2p1...> ... </assessmentItem>'
list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);

var_dump($item);
var_dump($questions);
var_dump($manifest);
```


### Converting QTI Manifest XML string to Learnosity JSON

The result from this function is highly opiniated and might not be what you want.

The `$activity` is generated based on assessmentItem <resources>(s) with item references assumed to be the resource identifier and status set to be `published`. You might want to use the actual assessment item identifier or your custom identifier instead.
The `$activityTags` is generated based on manifest metadata and the `$itemsTags` is based on the corresponding `resource` metadata. It simply flatten the XML structure with dot notation which you may need to parse it a bit more to suits your preferred Learnosity tags structure, ie. 
 
```
{
    "educational": [
        "intendedEndUserRole:source:IMSGLC_CC_Rolesv1p3",
        "intendedEndUserRole:source:value:Learner"
    ],
    "lifeCycle": [
        "version:string:Published"
    ]
}
```

Example usage

```
use LearnosityQti\Converter;

$xmlString = '<?xml version="1.0" encoding="utf-8"?><manifest ...> ... </manifest>'
list($activity, $activityTags, $itemsTags) = Converter::convertQtiManifestToLearnosity($xmlString);

var_dump($activity);
var_dump($activityTags);
var_dump($itemsTags);
```

## Development

### Running unit tests

You can run unit and integration tests: 

```
php vendor/bin/phpunit
```

### Updating schemas 

We relies on JSON schemas to generate entities objects used in this library. From time to time, schemas got updated and we need to update our PHP objects.
To do this, you will need to go update our schema located at `src/Config/resources/schemas/questions.json` with content from: `http://schemas.learnosity.com/stable/questions/`.
and run our build script:

```
php build.php
```

Running this build script will does 2 things:
 * Update our PHP entities objects at `src/Entities/*` in accordance to the schemas specified
 * Update our `documentation.html` according to specification at `src/**/Documentation/*` 

You might need to fix the mapper logic to suits our new schemas or update the tests if tests are failing. 

### Documentations

Documentation is automatically generated based on specifications at `src/**/Documentation/*` and 
it is located at `documentation.html` via our build script:
 
 ```
 php build.php
 ```

It's designed to a single page documentation that summarised the capabilities of this QTI conversion library.

If you do add up new features, or change the logic of mapping implementation, please also update the documentation.
