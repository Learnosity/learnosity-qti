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

$xmlString = '<assessmentItem ...> ... </assessmentItem>'
list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);

var_dump($item);
var_dump($questions);
var_dump($manifest);
```

