<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if ('BE' === TYPO3_MODE) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('be_users', 'EXT:begroups_roles/Resources/Private/Language/locallang_csh.xlf');

    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
        'begroups-roles-switchUserGroup',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:begroups_roles/Resources/Public/Icons/SwitchUserGroup.png',
        ]
    );
}
