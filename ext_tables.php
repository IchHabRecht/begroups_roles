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

	// Register Ajax script
	if (version_compare(TYPO3_branch, '6.2', '<')) {
		$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['RoleSwitcher::setUserGroup'] =
			t3lib_extMgm::extPath('begroups_roles') . 'Classes/ToolbarItem/RoleSwitcher.php:Tx_BegroupsRoles_ToolbarItem_RoleSwitcher->setUserGroup';
	} else {
		$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['RoleSwitcher::setUserGroup'] = array(
			'callbackMethod' => 'Tx_BegroupsRoles_ToolbarItem_RoleSwitcher->setUserGroup',
			'csrfTokenCheck' => TRUE,
		);
	}

}

?>