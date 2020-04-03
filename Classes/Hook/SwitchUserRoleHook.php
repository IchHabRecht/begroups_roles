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

use Doctrine\DBAL\Connection;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Sets current user group
 */
class SwitchUserRoleHook
{
    /**
     * @var BackendUserAuthentication
     */
    protected $backendUser;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(
        BackendUserAuthentication $backendUser = null,
        Connection $connection = null
    ) {
        $this->backendUser = $backendUser ?: $GLOBALS['BE_USER'];
        $this->connection = $connection ?: GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->backendUser->user_table);
    }

    /**
     * Assign user group from session data
     */
    public function setUserGroup()
    {
        if (empty($this->backendUser->user['tx_begroupsroles_enabled'])) {
            return;
        }

        $role = $this->backendUser->getSessionData('tx_begroupsroles_role');
        if ($role === null) {
            $role = 0;
            $this->backendUser->user['tx_begroupsroles_groups'] = implode(',', $this->getUsergroups($this->backendUser->user[$this->backendUser->usergroup_column]));
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder->update($this->backendUser->user_table)
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($this->backendUser->user['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('tx_begroupsroles_groups', $this->backendUser->user['tx_begroupsroles_groups'])
                ->execute();
        }

        $possibleUsergroups = GeneralUtility::intExplode(',', $this->backendUser->user['tx_begroupsroles_groups'], true);
        if (empty($role) && !empty($this->backendUser->user['tx_begroupsroles_limit'])) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $expressionBuilder = $queryBuilder->expr();
            $rows = $queryBuilder->select('uid')
                ->from($this->backendUser->usergroup_table)
                ->where(
                    $expressionBuilder->in(
                        'uid',
                        $queryBuilder->createNamedParameter($possibleUsergroups, Connection::PARAM_INT_ARRAY)
                    ),
                    $expressionBuilder->eq(
                        'tx_begroupsroles_isrole',
                        $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)
                    )
                )
                ->execute()
                ->fetchAll();

            $rows = array_combine(array_map('intval', array_column($rows, 'uid')), $rows);
            $orderedUsergroups = array_values(array_keys(array_intersect_key(array_flip($possibleUsergroups), $rows)));

            $role = !empty($orderedUsergroups[0]) ? $orderedUsergroups[0] : 0;
        }
        if (in_array($role, $possibleUsergroups, true)) {
            $this->backendUser->user[$this->backendUser->usergroup_column] = $role;
            if (!empty($this->backendUser->user['admin'])) {
                $this->backendUser->user['options'] |= Permission::PAGE_SHOW | Permission::PAGE_EDIT;
                $this->backendUser->user['admin'] = 0;
            }
        } else {
            $role = 0;
        }
        $this->backendUser->setAndSaveSessionData('tx_begroupsroles_role', $role);
    }

    /**
     * @param int $groupList
     * @param array $processedUsergroups
     * @return array
     */
    protected function getUsergroups($groupList, $processedUsergroups = [])
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(HiddenRestriction::class));
        $expressionBuilder = $queryBuilder->expr();
        $statement = $queryBuilder->select('uid', 'subgroup')
            ->from($this->backendUser->usergroup_table)
            ->where(
                $expressionBuilder->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                ),
                $expressionBuilder->in(
                    'uid',
                    $queryBuilder->createNamedParameter(
                        GeneralUtility::intExplode(',', $groupList, true),
                        Connection::PARAM_INT_ARRAY
                    )
                ),
                $expressionBuilder->orX(
                    $expressionBuilder->eq(
                        'lockToDomain',
                        $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)
                    ),
                    $expressionBuilder->isNull('lockToDomain'),
                    $expressionBuilder->eq(
                        'lockToDomain',
                        $queryBuilder->createNamedParameter(GeneralUtility::getIndpEnv('HTTP_HOST'), \PDO::PARAM_STR)
                    )
                )
            )
            ->execute();

        $usergroups = [];
        while ($row = $statement->fetch()) {
            if (isset($processedUsergroups[$row['uid']])) {
                continue;
            }

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

        return $usergroups;
    }
}
