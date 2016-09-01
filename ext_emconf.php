<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "begroups_roles".
 *
 * Auto generated 10-04-2014 00:49
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Backend user roles',
	'description' => 'Use backend user groups as switchable roles',
	'category' => 'module',
	'author' => 'Nicole Cordes',
	'author_email' => 'cordes@cps-it.de',
	'author_company' => 'CPS-IT GmbH',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '2.0.0-dev',
	'constraints' => array(
		'depends' => array(
			'typo3' => '7.6.0-8.3.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:11:{s:9:"ChangeLog";s:4:"1acc";s:12:"ext_icon.gif";s:4:"c9bc";s:14:"ext_tables.php";s:4:"1336";s:14:"ext_tables.sql";s:4:"70cf";s:24:"Classes/Hook/Backend.php";s:4:"4c49";s:25:"Classes/Hook/Userauth.php";s:4:"1d44";s:36:"Classes/ToolbarItem/RoleSwitcher.php";s:4:"cff5";s:43:"Resources/Private/Language/locallang_be.xml";s:4:"8e14";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"254f";s:42:"Resources/Public/Icons/SwitchUserGroup.png";s:4:"37b0";s:43:"Resources/Public/Javascript/roleswitcher.js";s:4:"e578";}',
);

?>