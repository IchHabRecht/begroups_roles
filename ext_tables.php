<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if ('BE' === TYPO3_MODE) {
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
        'begroups-roles-switchUserGroup',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:begroups_roles/Resources/Public/Icons/SwitchUserGroup.png',
        ]
    );
}
