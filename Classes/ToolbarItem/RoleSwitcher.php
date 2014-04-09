<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Nicole Cordes <cordes@cps-it.de>, CPS-IT GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Renders roles switcher to toolbar
 */
class Tx_BegroupsRoles_ToolbarItem_RoleSwitcher implements backend_toolbarItem {

	/**
	 * @var TYPO3backend
	 */
	protected $backendReference;

	/**
	 * @var array
	 */
	protected $userRecord;

	/**
	 * Constructor that receives a back reference to the backend
	 *
	 * @param TYPO3backend $backendReference
	 */
	public function __construct(TYPO3backend &$backendReference = NULL) {
		$this->backendReference = $backendReference;
		$this->userRecord = t3lib_BEfunc::getRecord('be_users', $GLOBALS['BE_USER']->user['uid']);
	}

	/**
	 * Checks whether the user has access to this toolbar item
	 *
	 * @return  bool true if user has access, false if not
	 */
	public function checkAccess() {
		return !empty($GLOBALS['BE_USER']->user['tx_begroupsroles_enabled'])
			&& strpos($this->userRecord[$GLOBALS['BE_USER']->usergroup_column], ',') !== FALSE;
	}

	/**
	 * Renders the toolbar item
	 *
	 * @return string the toolbar item rendered as HTML string
	 */
	public function render() {
		$this->addJavascriptToBackend();
		$userGroups = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'uid, title',
			'be_groups',
			'uid IN (' . $GLOBALS['TYPO3_DB']->cleanIntList($this->userRecord[$GLOBALS['BE_USER']->usergroup_column]) . ')',
			'',
			'title ASC'
		);
		$options = '';
		foreach ($userGroups as $group) {
			$options .= '<option value="' . rawurlencode($group['uid']) . '"' .
				($GLOBALS['BE_USER']->user[$GLOBALS['BE_USER']->usergroup_column] == $group['uid'] ? ' selected="selected"' : '') .
				'>' . htmlspecialchars($group['title']) . '</option>';
		}
		unset($group);

		return t3lib_iconWorks::getSpriteIcon('extensions-begroups-roles-switchUserGroup') .
			' <select name="tx-begroupsroles-usergroup" style="margin-right: 10px;">' .
				'<option value="0">' . $GLOBALS['LANG']->sL('LLL:EXT:begroups_roles/Resources/Private/Language/locallang_be.xml:all', 1) . '</option>' .
				$options .
			'</select>';
	}

	/**
	 * Returns additional attributes for the list item in the toolbar
	 *
	 * @return string list item HTML attributes
	 */
	public function getAdditionalAttributes() {
		return ' id="tx-begroupsroles-roleswitcher"';
	}

	/**
	 * Adds the necessary javascript to the backend
	 *
	 * @return	void
	 */
	protected function addJavascriptToBackend() {
		$this->backendReference->addJavascriptFile(t3lib_extMgm::extRelPath('begroups_roles') . 'Resources/Public/Javascript/roleswitcher.js');
	}

	/**
	 * Sets the new user group by ajax request
	 *
	 * @return bool
	 */
	public function setUserGroup() {
		$userGroup = (int) t3lib_div::_GP('userGroup');
		if ($userGroup <= 0) {
			$userGroup = 0;
		} else {
			$userRecord = t3lib_BEfunc::getRecord('be_users', $GLOBALS['BE_USER']->user['uid']);
			if (!t3lib_div::inList($userRecord[$GLOBALS['BE_USER']->usergroup_column], $userGroup)) {
				$userGroup = 0;
			}
		}

		$GLOBALS['BE_USER']->setAndSaveSessionData('tx_begroupsroles_role', $userGroup);

		return TRUE;
	}
}

?>