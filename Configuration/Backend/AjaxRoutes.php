<?php

return [
    'role_switch' => [
        'path' => '/role/switch',
        'target' => \CPSIT\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher::class . '::switchRoleAction'
    ],
];
