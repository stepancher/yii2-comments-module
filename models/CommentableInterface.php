<?php
/**
 * Created by PhpStorm.
 * User: ubasma
 * Date: 25.11.15
 * Time: 21:00
 */

namespace stepancher\comments\models;


interface CommentableInterface
{
    /**
     * Может ли пользователь оставлять комментарии
     * @param int $idModel
     * @param int $idUser
     * @param $idParentComment - id родительского комментария
     * @return bool
     */
    public static function checkCanComment($idModel, $idUser, $idParentComment);

    /**
     * Вызов пользовательского метода в afterSave
     * Отдает параметры из afterSave ($insert, $changedAttributes)
     * и массивы из таблиц модуля
     * @param bool $insert
     * @param array $changedAttributes
     * @param array $comments
     * @param array $comments_models
     * @return mixed
     */
    public static function  afterSaveComment($insert, $changedAttributes, $comments, $comments_models);
}