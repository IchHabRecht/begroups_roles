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

Ext.onReady(function() {
	Ext.get('tx-begroupsroles-roleswitcher').first('select').on('change', function () {
		if (TYPO3.settings && TYPO3.settings.ajaxUrls) {
			var url = TYPO3.settings.ajaxUrls['RoleSwitcher::setUserGroup'];
		} else {
			var url = 'ajax.php?ajaxID=RoleSwitcher::setUserGroup';
		}
		Ext.Ajax.request({
			url: url,
			method: 'GET',
			params: {
				'userGroup': this.getValue()
			},
			scope: this,
			success: function(response, opts) {
				location.reload();
			}
		});
	});
});