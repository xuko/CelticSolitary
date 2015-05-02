<?php   // app/scripts_aux/list_users.php

require_once __DIR__ . '/../../config/bootstrap.php';

$entityManager = GetEntityManager();

$userRepository = $entityManager->getRepository('TDW\UserApi\Entity\User');
$users = $userRepository->findAll();

$items = 0;
echo sprintf("  %2s: %16s %7s %7s %s\n", 'Id', 'Username:', 'Active:', 'Admin:', 'Group:');
foreach ($users as $user) {
    echo sprintf("- %2d: %16s %7s %7s %s\n", 
            $user->getId(), $user->getUsername(), $user->getIsActive(),
            $user->getIsAdmin(), $user->getGroup());
    $items++;
}

echo "\nTotal: $items users.\n\n";
