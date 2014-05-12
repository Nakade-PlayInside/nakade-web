<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 12.05.14
 * Time: 19:10
 */

namespace Permission\Entity;


/**
 * Interface RoleInterface
 *
 * @package Permission\Entity
 */
interface RoleInterface
{
    const DEFAULT_ROLE = 'everyone';
    const ROLE_GUEST = 'guest';
    const ROLE_USER = 'user';
    const ROLE_MEMBER = 'member';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_ADMIN = 'admin';
}