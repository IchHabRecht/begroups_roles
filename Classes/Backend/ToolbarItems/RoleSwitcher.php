<?php
namespace IchHabRecht\BegroupsRoles\Backend\ToolbarItems;

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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Renders roles switcher to toolbar
 */
class RoleSwitcher implements ToolbarItemInterface
{
    /**
     * @var BackendUserAuthentication
     */
    protected $backendUser;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var IconFactory
     */
    private $iconFactory;

    /**
     * @var LanguageService
     */
    protected $languageService;

    /**
     * @var PageRenderer
     */
    protected $pageRenderer;

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var int
     */
    protected $role = 0;

    public function __construct(
        BackendUserAuthentication $backendUser = null,
        Connection $connection = null,
        IconFactory $iconFactory = null,
        LanguageService $languageService = null,
        PageRenderer $pageRenderer = null
    ) {
        $this->backendUser = $backendUser ?: $GLOBALS['BE_USER'];
        $this->connection = $connection ?: GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->backendUser->user_table);
        $this->iconFactory = $iconFactory ?: GeneralUtility::makeInstance(IconFactory::class);
        $this->languageService = $languageService ?: $GLOBALS['LANG'];
        $this->pageRenderer = $pageRenderer ?: GeneralUtility::makeInstance(PageRenderer::class);
    }

    /**
     * Checks whether the user has access to this toolbar item
     *
     * @return  bool
     */
    public function checkAccess()
    {
        if (empty($this->backendUser->user['tx_begroupsroles_enabled'])) {
            return false;
        }

        $this->role = (int)$this->backendUser->getSessionData('tx_begroupsroles_role');

        $queryBuilder = $this->connection->createQueryBuilder();
        $expressionBuilder = $queryBuilder->expr();
        $rows = $queryBuilder->select('uid', 'title')
            ->from($this->backendUser->usergroup_table)
            ->where(
                $expressionBuilder->in(
                    'uid',
                    $queryBuilder->createNamedParameter(
                        GeneralUtility::intExplode(',', $this->backendUser->user['tx_begroupsroles_groups'], true),
                        Connection::PARAM_INT_ARRAY
                    )
                ),
                $expressionBuilder->eq(
                    'tx_begroupsroles_isrole',
                    $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)
                )
            )
            ->orderBy('title')
            ->execute()
            ->fetchAll();

        $this->groups = array_combine(array_map('intval', array_column($rows, 'uid')), $rows);

        return !empty($this->groups);
    }

    /**
     * Render "item" part of this toolbar
     *
     * @return string
     */
    public function getItem()
    {
        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/BegroupsRoles/Toolbar/RoleSwitcher');

        $title = $this->languageService->sL('LLL:EXT:begroups_roles/Resources/Private/Language/locallang_be.xlf:switch_group');
        $groupTitle = !empty($this->groups[$this->role])
            ? $this->groups[$this->role]['title']
            : $this->languageService->sL('LLL:EXT:begroups_roles/Resources/Private/Language/locallang_be.xlf:all_groups');

        return '<span title="' . htmlspecialchars($title) . '">'
            . $this->iconFactory->getIcon('begroups-roles-switchUserGroup', Icon::SIZE_SMALL)->render()
            . ' [' . htmlspecialchars($groupTitle) . ']'
            . '</span>';
    }

    /**
     * TRUE if this toolbar item has a collapsible drop down
     *
     * @return bool
     */
    public function hasDropDown()
    {
        return true;
    }

    /**
     * Render "drop down" part of this toolbar
     *
     * @return string Drop down HTML
     */
    public function getDropDown()
    {
        $groupIcon = $this->iconFactory->getIcon('status-user-group-backend', Icon::SIZE_SMALL)->render('inline');

        $result = [];
        $result[] = '<ul class="dropdown-list">';

        if (!empty($this->role) && empty($this->backendUser->user['tx_begroupsroles_limit'])) {
            $result[] = '<li>';
            $result[] = '<a href="#" class="dropdown-list-link" data-role="0">' . $groupIcon . ' '
                . htmlspecialchars($this->languageService->sL('LLL:EXT:begroups_roles/Resources/Private/Language/locallang_be.xlf:all_groups'))
                . '</a>';
            $result[] = '</li>';
        }

        foreach ($this->groups as $group) {
            if ($this->role !== (int)$group['uid']) {
                $result[] = '<li>';
                $result[] = '<a href="#" class="dropdown-list-link" data-role="' . (int)$group['uid'] . '">'
                    . $groupIcon . ' ' . htmlspecialchars($group['title'])
                    . '</a>';
                $result[] = '</li>';
            }
        }

        $result[] = '</ul>';

        return implode(LF, $result);
    }

    /**
     * Returns an array with additional attributes added to containing <li> tag of the item.
     *
     * @return array
     */
    public function getAdditionalAttributes()
    {
        return [];
    }

    /**
     * Returns an integer between 0 and 100 to determine the position of this item relative to others
     *
     * @return int
     */
    public function getIndex()
    {
        return 80;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function switchRoleAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $newRole = (int)GeneralUtility::_POST('role');
        if ($newRole <= 0 || !GeneralUtility::inList($this->backendUser->user['tx_begroupsroles_groups'], $newRole)) {
            $newRole = 0;
        }

        $this->backendUser->setAndSaveSessionData('tx_begroupsroles_role', $newRole);

        return $response;
    }
}
