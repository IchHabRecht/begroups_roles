<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "begroups_roles".
 *
 * Auto generated 28-04-2017 11:36
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
  'version' => '2.0.3',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '7.6.0-8.7.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:16:{s:9:"ChangeLog";s:4:"87f8";s:13:"composer.json";s:4:"996a";s:12:"ext_icon.gif";s:4:"c9bc";s:17:"ext_localconf.php";s:4:"f1a3";s:14:"ext_tables.php";s:4:"57b7";s:14:"ext_tables.sql";s:4:"70cf";s:45:"Classes/Backend/ToolbarItems/RoleSwitcher.php";s:4:"284e";s:35:"Classes/Hook/SwitchUserRoleHook.php";s:4:"cee2";s:36:"Configuration/Backend/AjaxRoutes.php";s:4:"a5b6";s:40:"Configuration/TCA/Overrides/be_users.php";s:4:"336e";s:46:"Resources/Private/Language/de.locallang_be.xlf";s:4:"5211";s:46:"Resources/Private/Language/de.locallang_db.xlf";s:4:"fc2c";s:43:"Resources/Private/Language/locallang_be.xlf";s:4:"2da4";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"447e";s:42:"Resources/Public/Icons/SwitchUserGroup.png";s:4:"37b0";s:51:"Resources/Public/JavaScript/Toolbar/RoleSwitcher.js";s:4:"26aa";}',
);

