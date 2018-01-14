# TYPO3 Extension begroups_roles

[![Latest Stable Version](https://img.shields.io/packagist/v/ichhabrecht/begroups-roles.svg)](https://packagist.org/packages/ichhabrecht/begroups-roles)
[![Build Status](https://img.shields.io/travis/IchHabRecht/begroups_roles/master.svg)](https://travis-ci.org/IchHabRecht/begroups_roles)
[![StyleCI](https://styleci.io/repos/18537810/shield?branch=master)](https://styleci.io/repos/18537810)

Use backend user groups as switchable roles

![Role switcher](Documentation/Images/role_switcher.png)

## Installation

Simply install the extension with Composer or the Extension Manager.

```
composer require ichhabrecht/begroups-roles
```

## Usage

1. Add multiple backend groups, each for one purpose
   - Tick the checkbox `Use this group as role` 
   - Limit the modules, tables and database mount to the purpose
   
2. For convenience add the created groups to a parent group 

3. Assign the created (parent) group to backend users
   - Tick the checkbox `Use groups as roles`
   - To allow only one role group simultaneously, tick the checkbox `Restrict to one group`
