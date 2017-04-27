<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if ('BE' === TYPO3_MODE) {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1472569541] =
        \CPSIT\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher::class;

    // Register hook to adjust current user group
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['begroups_roles'] =
        \CPSIT\BegroupsRoles\Hook\SwitchUserRoleHook::class . '->setUserGroup';
}
