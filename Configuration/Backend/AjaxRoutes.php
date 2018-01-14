<?php

return [
    'role_switch' => [
        'path' => '/role/switch',
        'target' => \IchHabRecht\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher::class . '::switchRoleAction',
    ],
];
