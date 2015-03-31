<?php

namespace stepancher\comments;

use yii\base\BootstrapInterface;

/**
 * Gallery module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module URL rules.
        $app->getUrlManager()->addRules(
            [
                'POST <_m:galleries>' => '<_m>/default/create',
                '<_m:galleries>' => '<_m>/default/index',
                '<_m:galleries>/<id:\d+>-<alias:[a-zA-Z0-9_-]{1,100}+>' => '<_m>/default/view',
            ]
        );

        // Add module I18N category.
        if (!isset($app->i18n->translations['stepancher/comments']) && !isset($app->i18n->translations['stepancher/*'])) {
            $app->i18n->translations['stepancher/comments'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@stepancher/comments/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'stepancher/comments' => 'comments.php',
                ]
            ];
        }
        if (!isset($app->i18n->translations['stepancher/comments-models']) && !isset($app->i18n->translations['stepancher/*'])) {
            $app->i18n->translations['stepancher/comments-models'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@stepancher/comments/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'stepancher/comments-models' => 'comments-models.php',
                ]
            ];
        }
    }
}
