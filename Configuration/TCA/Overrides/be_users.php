<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$tempColumns = [
    'tx_begroupsroles_enabled' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:begroups_roles/Resources/Private/Language/locallang_db.xlf:be_users.tx_begroupsroles_enabled',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ],
    ],
    'tx_begroupsroles_limit' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:begroups_roles/Resources/Private/Language/locallang_db.xlf:be_users.tx_begroupsroles_limit',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_users', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('be_users', 'tx_begroupsroles', 'tx_begroupsroles_enabled, tx_begroupsroles_limit');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_users', '--palette--;LLL:EXT:begroups_roles/Resources/Private/Language/locallang_db.xlf:be_users.tx_begroupsroles_title;tx_begroupsroles', '', 'after:usergroup');
