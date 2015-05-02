<?php // app/scripts_aux/list_groups.php

require_once __DIR__ . '/../../config/bootstrap.php';

$entityManager = GetEntityManager();

$groupRepository = $entityManager->getRepository('TDW\UserApi\Entity\Group');
$groups = $groupRepository->findAll();

$items = 0;
echo sprintf("  %2s: %32s %s\n", 'Id', 'Groupname:', 'Description:');
foreach ($groups as $group) {
    echo sprintf("- %2d: %32s %s\n", $group->getId(), $group->getGroupname(), $group->getDescription());
    $items++;
}

echo "\nTotal: $items groups.\n\n";
