<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
        'begroups-roles-switchUserGroup',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:begroups_roles/Resources/Public/Icons/SwitchUserGroup.png',
        ]
    );

	// Register hook to adjust current user group
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['begroups_roles'] =
		'EXT:begroups_roles/Classes/Hook/Userauth.php:Tx_BegroupsRoles_Hook_Userauth->setUserGroup';
}

?>