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
     * @return bool
     */
    public static function checkCanComment($idModel, $idUser);
}