<?php
namespace IchHabRecht\BegroupsRoles\Tests\Functional\Backend\ToolbarItems;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Nicole Cordes <typo3@cordes.co>
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

use IchHabRecht\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Core\Bootstrap;

class RoleSwitcherTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/begroups_roles',
    ];

    /**
     * @var RoleSwitcher
     */
    protected $roleSwitcher;

    protected function setUp()
    {
        parent::setUp();

        $fixturePath = ORIGINAL_ROOT . 'typo3conf/ext/begroups_roles/Tests/Functional/Fixtures/Database/';
        $this->importDataSet($fixturePath . 'be_groups.xml');
        $this->importDataSet($fixturePath . 'be_users.xml');

        Bootstrap::getInstance()->initializeLanguageObject();

        $this->roleSwitcher = new RoleSwitcher();
    }

    /**
     * @test
     */
    public function checkAccessReturnsFalseWhenBeUsersFlagNotSet()
    {
        $this->setUpBackendUserFromFixture(2);

        $this->assertFalse($this->roleSwitcher->checkAccess());
    }

    /**
     * @test
     */
    public function checkAccessReturnsFalseWhenBeUsersFlagSetWithoutRoles()
    {
        $this->setUpBackendUserFromFixture(3);
        $this->getDatabaseConnection()->updateArray('be_groups', ['pid' => 0], ['tx_begroupsroles_isrole' => 0]);

        $this->assertFalse($this->roleSwitcher->checkAccess());
    }

    /**
     * @test
     */
    public function checkAccessReturnsTrueWhenBeUsersFlagSetWithRoles()
    {
        $this->setUpBackendUserFromFixture(3);

        $this->assertTrue($this->roleSwitcher->checkAccess());
    }

    /**
     * @test
     */
    public function getItemReturnsAllGroupsLabel()
    {
        $this->setUpBackendUserFromFixture(3);

        $this->assertContains('[All groups]', $this->roleSwitcher->getItem());
    }

    /**
     * @test
     */
    public function getItemNotReturnsAllGroupsLabelForLimitedUser()
    {
        $this->setUpBackendUserFromFixture(4);

        $this->assertTrue($this->roleSwitcher->checkAccess());
        $this->assertNotContains('[All groups]', $this->roleSwitcher->getItem());
    }

    /**
     * @test
     */
    public function getItemReturnsFirstGroupForLimitedUser()
    {
        $this->setUpBackendUserFromFixture(4);

        $this->assertTrue($this->roleSwitcher->checkAccess());
        $this->assertContains('[Maintain News]', $this->roleSwitcher->getItem());
    }
}
