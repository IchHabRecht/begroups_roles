<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "begroups_roles".
 *
 * Auto generated 08-04-2014 01:06
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
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:10:{s:9:"ChangeLog";s:4:"dca3";s:12:"ext_icon.gif";s:4:"c9bc";s:14:"ext_tables.php";s:4:"1336";s:14:"ext_tables.sql";s:4:"70cf";s:24:"Classes/Hook/Backend.php";s:4:"4c49";s:25:"Classes/Hook/Userauth.php";s:4:"2d81";s:36:"Classes/ToolbarItem/RoleSwitcher.php";s:4:"11cd";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"c4d1";s:42:"Resources/Public/Icons/SwitchUserGroup.png";s:4:"37b0";s:43:"Resources/Public/Javascript/roleswitcher.js";s:4:"e578";}',
);

?>