<?php
namespace IchHabRecht\BegroupsRoles\Hook;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
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

        if (!empty($backendUser->user['tx_begroupsroles_enabled'])) {
            $role = $backendUser->getSessionData('tx_begroupsroles_role');
            if ($role === null) {
                $role = 0;
                $backendUser->user['tx_begroupsroles_groups'] = implode(',', $this->getUsergroups($backendUser->user[$backendUser->usergroup_column]));
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    $backendUser->user_table,
                    'uid=' . $backendUser->user['uid'],
                    [
                        'tx_begroupsroles_groups' => $backendUser->user['tx_begroupsroles_groups'],
                    ]
                );
            }
            if (empty($role) && !empty($backendUser->user['tx_begroupsroles_limit'])) {
                $databaseConnection = $this->getDatabaseConnection();
                $possibleUsergroups = $databaseConnection->cleanIntList($backendUser->user['tx_begroupsroles_groups']);
                $group = $databaseConnection->exec_SELECTgetSingleRow(
                    'uid',
                    $backendUser->usergroup_table,
                    'uid IN (' . $possibleUsergroups . ') AND tx_begroupsroles_isrole=1'
                    . BackendUtility::deleteClause($backendUser->usergroup_table),
                    '',
                    'FIND_IN_SET(uid, ' . $databaseConnection->fullQuoteStr($possibleUsergroups, $backendUser->usergroup_table) . ')'
                );
                $role = !empty($group['uid']) ? (int)$group['uid'] : 0;
            }
            if (!empty($role)
                && GeneralUtility::inList($backendUser->user['tx_begroupsroles_groups'], $role)
            ) {
                $backendUser->user[$backendUser->usergroup_column] = $role;
                if (!empty($backendUser->user['admin'])) {
                    $backendUser->user['options'] |= Permission::PAGE_SHOW | Permission::PAGE_EDIT;
                    $backendUser->user['admin'] = 0;
                }
            } else {
                $role = 0;
            }
            $backendUser->setAndSaveSessionData('tx_begroupsroles_role', $role);
        }
    }

    /**
     * @param int $groupList
     * @param array $processedUsergroups
     * @return array
     */
    protected function getUsergroups($groupList, $processedUsergroups = [])
    {
        $backendUser = $this->getBackendUser();
        $databaseConnection = $this->getDatabaseConnection();
        $groupList = GeneralUtility::intExplode(',', $groupList, true);
        $result = $databaseConnection->exec_SELECTquery(
            'uid, subgroup',
            $backendUser->usergroup_table,
            'deleted=0 AND hidden=0 AND pid=0 AND uid IN (' . implode(',', $groupList) . ')'
            . ' AND (lockToDomain=\'\' OR lockToDomain IS NULL OR lockToDomain='
            . $databaseConnection->fullQuoteStr(GeneralUtility::getIndpEnv('HTTP_HOST'), $backendUser->usergroup_table)
            . ')'
        );
        $usergroups = [];
        while ($row = $result->fetch_assoc()) {
            if (!isset($processedUsergroups[$row['uid']])) {
                $processedUsergroups[$row['uid']] = $row['uid'];
                $usergroups[$row['uid']] = $row['uid'];
                if (!empty($row['subgroup'])) {
                    $subgroupList = GeneralUtility::intExplode(',', $row['subgroup'], true);
                    $subgroups = $this->getUsergroups($row['subgroup'], $processedUsergroups);
                    if (!empty($subgroups)) {
                        $usergroups = array_merge(
                            $usergroups,
                            array_intersect($subgroupList, $subgroups),
                            array_diff($subgroups, $subgroupList)
                        );
                    }
                }
            }
        }
        $databaseConnection->sql_free_result($result);

        return $usergroups;
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
