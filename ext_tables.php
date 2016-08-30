<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {
	// Register sprite icons
	$icons = array(
		'switchUserGroup' => t3lib_extMgm::extRelPath('begroups_roles') . 'Resources/Public/Icons/SwitchUserGroup.png',
	);
	t3lib_SpriteManager::addSingleIcons($icons, 'begroups-roles');

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

	// Register hook to render the role switcher
	$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems']['begroups_roles'] =
		t3lib_extMgm::extPath('begroups_roles') . 'Classes/Hook/Backend.php';
}

?>