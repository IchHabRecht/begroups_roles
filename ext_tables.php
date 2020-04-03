<?php
defined('TYPO3_MODE') || die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1472569541] =
        \IchHabRecht\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher::class;

    // Register hook to adjust current user group
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['begroups_roles'] =
        \IchHabRecht\BegroupsRoles\Hook\SwitchUserRoleHook::class . '->setUserGroup';

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('be_groups', 'EXT:begroups_roles/Resources/Private/Language/locallang_csh.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('be_users', 'EXT:begroups_roles/Resources/Private/Language/locallang_csh.xlf');

    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
        'begroups-roles-switchUserGroup',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:begroups_roles/Resources/Public/Icons/SwitchUserGroup.png',
        ]
    );
});
