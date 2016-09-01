<?php
namespace CPSIT\BegroupsRoles\Hook;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Nicole Cordes <cordes@cps-it.de>, CPS-IT GmbH
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

use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Sets current user group
 */
class SwitchUserRoleHook
{
    /**
     * Assign user group from session data
     */
    public function setUserGroup()
    {
        $backendUser = $this->getBackendUser();
        if (!empty($backendUser->user['tx_begroupsroles_enabled'])
            && strpos($backendUser->user[$backendUser->usergroup_column], ',') !== false
        ) {
            $role = (int)$backendUser->getSessionData('tx_begroupsroles_role');
            if (!empty($role)
                && GeneralUtility::inList($backendUser->user[$backendUser->usergroup_column], $role)
            ) {
                $backendUser->user[$backendUser->usergroup_column] = $role;
                $backendUser->user['admin'] = 0;
            } else {
                $backendUser->setAndSaveSessionData('tx_begroupsroles_role', 0);
            }
        }
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
}
