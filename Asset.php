<?php

namespace stepancher\comments;

use yii\web\AssetBundle;

/**
 * Module asset bundle.
 */
class Asset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@stepancher/comments/assets';

    public $css = [
        'css/comments.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/comments.js'
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset'
    ];
}
