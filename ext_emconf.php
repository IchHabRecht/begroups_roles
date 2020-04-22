<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "begroups_roles".
 *
 * Auto generated 22-04-2020 13:08
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Backend user roles',
  'description' => 'Use backend user groups as switchable roles',
  'category' => 'module',
  'author' => 'Nicole Cordes',
  'author_email' => 'typo3@cordes.co',
  'author_company' => 'CPS-IT GmbH | biz-design.biz',
  'state' => 'stable',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 1,
  'version' => '3.1.1',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '8.7.0-10.4.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:28:{s:9:"ChangeLog";s:4:"37ea";s:7:"LICENSE";s:4:"b234";s:9:"README.md";s:4:"fdb8";s:13:"composer.json";s:4:"f39f";s:13:"composer.lock";s:4:"185c";s:12:"ext_icon.gif";s:4:"c9bc";s:14:"ext_tables.php";s:4:"597c";s:14:"ext_tables.sql";s:4:"3af5";s:16:"phpunit.xml.dist";s:4:"041c";s:24:"sonar-project.properties";s:4:"bb3d";s:45:"Classes/Backend/ToolbarItems/RoleSwitcher.php";s:4:"2fc6";s:35:"Classes/Hook/SwitchUserRoleHook.php";s:4:"a375";s:36:"Configuration/Backend/AjaxRoutes.php";s:4:"ae2a";s:41:"Configuration/TCA/Overrides/be_groups.php";s:4:"c5ca";s:40:"Configuration/TCA/Overrides/be_users.php";s:4:"2c9d";s:38:"Documentation/Images/role_switcher.png";s:4:"b8ab";s:27:"Resources/Private/.htaccess";s:4:"5cc8";s:46:"Resources/Private/Language/de.locallang_be.xlf";s:4:"5211";s:47:"Resources/Private/Language/de.locallang_csh.xlf";s:4:"b361";s:46:"Resources/Private/Language/de.locallang_db.xlf";s:4:"6732";s:43:"Resources/Private/Language/locallang_be.xlf";s:4:"2da4";s:44:"Resources/Private/Language/locallang_csh.xlf";s:4:"d279";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"1b28";s:42:"Resources/Public/Icons/SwitchUserGroup.png";s:4:"37b0";s:51:"Resources/Public/JavaScript/Toolbar/RoleSwitcher.js";s:4:"aba9";s:58:"Tests/Functional/Backend/ToolbarItems/RoleSwitcherTest.php";s:4:"0b04";s:48:"Tests/Functional/Fixtures/Database/be_groups.xml";s:4:"1e0d";s:47:"Tests/Functional/Fixtures/Database/be_users.xml";s:4:"036d";}',
);

