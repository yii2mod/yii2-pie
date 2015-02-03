<?php

namespace yii2mod\pie;

use yii\helpers\Json;
use yii2mod\pie\PieAsset;
use app\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class Pie
 * @author  Dmitry Semenov <disemx@gmail.com>
 * @see     http://d3pie.org/#generator
 * @see     http://d3pie.org/#docs
 * @package app\components\extensions\pie
 */
class Pie extends Widget
{
    /**
     * @var bool
     */
    public $skipOnEmpty = true;
    /**
     * @var
     */
    public $content;
    /**
     * @var
     */
    public $title;
    /**
     * @var array
     */
    public $options = [];
    /**
     * @var string
     */
    public $baseColor = '#8A67A4';
    /**
     * @var array
     */
    public $pieOptions = [
        'header' => [
            'title' => [
                'color' => '#999999',
                'fontSize' => 24,
                'font' => 'open sans',
            ],
            'subtitle' => [
                'color' => '#999999',
                'fontSize' => 12,
                'font' => 'open sans',
            ],
            'location' => 'top-center',
            'titleSubtitlePadding' => 0,
        ],
        'footer' => [
            'color' => '#999999',
            'fontSize' => 14,
            'font' => 'open sans',
            'location' => 'bottom-left',
        ],
        'size' => [
            'canvasHeight' => 350,
            'canvasWidth' => 400,
            'pieInnerRadius' => '90%',
            'pieOuterRadius' => '50%',
        ],
        'data' => [
            'sortOrder' => 'value-desc',
            'content' => [],
        ],
        'labels' => [
            'outer' => [
                'format' => 'label-value2',
                'pieDistance' => 5,
            ],
            'inner' => [
                'format' => 'none',
            ],
            'mainLabel' => [
                'fontSize' => 14,
            ],
            'percentage' => [
                'color' => '#ffffff',
                'fontSize' => 0,
                'decimalPlaces' => 0,
            ],
            'value' => [
                'color' => '#fff',
                'fontSize' => 16,
            ],
            'lines' => [
                'enabled' => false,
                'style' => 'straight',
            ],
            'truncation' => [
                'enabled' => true,
                'length' => 15
            ]
        ],
        "tooltips" => [
            "enabled" => true,
            "type" => "placeholder",
            "string" => "{label}: {value}"
        ],
        'effects' => [
            'pullOutSegmentOnClick' => [
                'effect' => 'none',
                'speed' => 1000,
                'size' => 8,
            ],
            'highlightSegmentOnMouseover' => false
        ],
        'misc' => [
            'gradient' => [
                'enabled' => false,
                'percentage' => 100,
            ],
            'colors' => [
                'segmentStroke' => 'none'
            ]
        ]
    ];


    /**
     * Runs widget
     */
    public function run()
    {
        $this->initOptions();
        return Html::tag('div', '', ['id' => $this->getId(),
            'style' => 'width: ' . $this->pieOptions['size']['canvasWidth'] . 'px;height: ' . $this->pieOptions['size']['canvasHeight'] . 'px;'
        ]);
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        if (empty($this->content) && $this->skipOnEmpty == false) {
            throw new InvalidConfigException("No content for widget data");
        } elseif (empty($this->content)) {
            return false;
        }


        $itemsCount = count($this->content);
        $colorStep = round(100 / $itemsCount);
        ArrayHelper::multisort($this->content, 'value', SORT_DESC);
        foreach ($this->content as $key => $item) {
            if (empty($this->content[$key]['color'])) {
                $nextColor = $this->adjustBrightness(isset($nextColor) ? $nextColor : $this->baseColor, $colorStep);
                $this->content[$key]['color'] = $nextColor;
                $this->content[$key]['label'] = $this->content[$key]['label'];
            }
        }

        $this->pieOptions['data']['content'] = $this->content;
        $this->pieOptions['header']['title']['text'] = $this->title;
        $this->pieOptions = ArrayHelper::merge($this->pieOptions, $this->options);
        $this->registerAssets();
    }

    /**
     * Register client assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        PieAsset::register($view);

        $js = 'var pie' . ucfirst($this->getId()) . ' = new d3pie(' . $this->getId() . ', ' . Json::encode($this->pieOptions) . ');';
        $view->registerJs($js);

    }

    /**
     * @param $hex
     * @param $steps
     *
     * @return string
     */
    function adjustBrightness($hex, $steps)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color = hexdec($color); // Convert to decimal
            $color = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }

}
