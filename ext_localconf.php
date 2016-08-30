<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

if ('BE' === TYPO3_MODE) {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1472569541] =
        \CPSIT\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher::class;
}
