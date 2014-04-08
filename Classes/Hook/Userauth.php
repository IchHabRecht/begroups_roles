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
 * Sets current user group
 */
class Tx_BegroupsRoles_Hook_Userauth {

	/**
	 * @param array $params
	 * @param t3lib_userAuth $parentObject
	 */
	public function setUserGroup($params, &$parentObject) {
		if (!empty($GLOBALS['BE_USER']->user['tx_begroupsroles_enabled'])
			&& strpos($GLOBALS['BE_USER']->user[$GLOBALS['BE_USER']->usergroup_column], ',') !== FALSE)
		{
			$sessionData = unserialize($GLOBALS['BE_USER']->user['ses_data']);
			if (empty($sessionData['tx_begroupsroles_role'])
				|| !t3lib_div::inList($GLOBALS['BE_USER']->user[$GLOBALS['BE_USER']->usergroup_column], (int) $sessionData['tx_begroupsroles_role']))
			{
				$userGroups = t3lib_div::intExplode(',', $GLOBALS['BE_USER']->user[$GLOBALS['BE_USER']->usergroup_column]);
				$sessionData['tx_begroupsroles_role'] = $userGroups[0];
				$GLOBALS['BE_USER']->setAndSaveSessionData('tx_begroupsroles_role', $userGroups[0]);
			}
			$GLOBALS['BE_USER']->user[$GLOBALS['BE_USER']->usergroup_column] = $sessionData['tx_begroupsroles_role'];
		}
	}
}

?>