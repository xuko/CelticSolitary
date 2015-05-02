<?php   // app/scripts_aux/create_user_admin.php

require_once __DIR__ . '/../../config/bootstrap.php';

use TDW\UserApi\Entity\User,
    TDW\UserApi\Entity\Group;

$em = GetEntityManager();

$group = new Group('Admin - ' . rand());
$em->persist($group);
$em->flush();

$user = new User();
$user->setUsername('tdw_admin' . rand());
$user->setEmail('tdw_admin' . rand() . '@example.com');
$user->setPassword('*tdw_admin*');
$user->setCreateTime(new \DateTime());
$user->setIsActive(TRUE);
$user->setIsAdmin(TRUE);
$user->setGroup($group);

$em->persist($user);
$em->flush();

