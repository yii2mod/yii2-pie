Pie generator
=============
Pie generator using d3pie.js

[![Latest Stable Version](https://poser.pugx.org/yii2mod/yii2-pie/v/stable)](https://packagist.org/packages/yii2mod/yii2-pie) [![Total Downloads](https://poser.pugx.org/yii2mod/yii2-pie/downloads)](https://packagist.org/packages/yii2mod/yii2-pie) [![License](https://poser.pugx.org/yii2mod/yii2-pie/license)](https://packagist.org/packages/yii2mod/yii2-pie)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2mod/yii2-pie "*"
```

or add

```
"yii2mod/yii2-pie": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?php echo Pie::widget([
    'id' => 'pie-id',
    'title' => 'Pie Title',
    'options' => [
        ...    
    ],
    'content' => [
        [
            'value' => 10,
            'color' => 'none',
            'label' => 'Label 1'
        ],
        [
            'value' => 30,
            'color' => '#fff',
            'label' => 'Label 2'
        ],
    ]
]); ?>
```
