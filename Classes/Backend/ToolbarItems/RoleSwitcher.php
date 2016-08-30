<?php
namespace CPSIT\BegroupsRoles\Backend\ToolbarItems;

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

use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Renders roles switcher to toolbar
 */
class RoleSwitcher implements ToolbarItemInterface
{
    /**
     * @var array
     */
    protected $groups = [];

    /**
     * Checks whether the user has access to this toolbar item
     *
     * @return  bool
     */
    public function checkAccess()
    {
        return !empty($this->getBackendUser()->user['tx_begroupsroles_enabled'])
            && strpos($this->getBackendUser()->user[$this->getBackendUser()->usergroup_column], ',') !== false;
    }

    /**
     * Render "item" part of this toolbar
     *
     * @return string
     */
    public function getItem()
    {
        $this->groups = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid, title',
            'be_groups',
            'uid IN (' . $this->getDatabaseConnection()->cleanIntList($this->getBackendUser()->user[$this->getBackendUser()->usergroup_column]) . ')',
            '',
            'title ASC',
            '',
            'uid'
        );

        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $title = $this->getLanguageService()->sL('LLL:EXT:begroups_roles/Resources/Private/Language/locallang_be.xlf:switch_group');
        $role = $this->getBackendUser()->getSessionData('tx_begroupsroles_role');
        $groupTitle = !empty($this->groups[$role])
            ? $this->groups[$role]['title']
            : $this->getLanguageService()->sL('LLL:EXT:begroups_roles/Resources/Private/Language/locallang_be.xlf:all_groups');

        return '<span title="' . htmlspecialchars($title) . '">'
            . $iconFactory->getIcon('begroups-roles-switchUserGroup', Icon::SIZE_SMALL)->render()
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
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $groupIcon = $iconFactory->getIcon('status-user-group-backend', Icon::SIZE_SMALL)->render('inline');

        $result = [];
        $result[] = '<ul class="dropdown-list">';
        $result[] = '<li>';
        $result[] = '<a href="#" class="dropdown-list-link">' . $groupIcon . ' '
            . htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:begroups_roles/Resources/Private/Language/locallang_be.xlf:all_groups'))
            . '</a>';
        $result[] = '</li>';

        foreach ($this->groups as $group) {
            $result[] = '<li>';
            $result[] = '<a href="#" class="dropdown-list-link">' . $groupIcon . ' ' . htmlspecialchars($group['title']) . '</a>';
            $result[] = '</li>';
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

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
