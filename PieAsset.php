<?php
namespace yii2mod\pie;

use yii\web\AssetBundle;

/**
 * Class D3Asset
 * @package app\assets
 */
class PieAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower';


    /**
     * @var array
     */
    public $js = [
        'd3/d3.min.js',
        'd3pie/d3pie/d3pie.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
