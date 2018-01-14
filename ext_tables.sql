#
# Table structure for table 'be_users'
#
CREATE TABLE be_groups (
    tx_begroupsroles_isrole tinyint(4) UNSIGNED DEFAULT '0' NOT NULL
);

#
# Table structure for table 'be_users'
#
CREATE TABLE be_users (
    tx_begroupsroles_enabled tinyint(4) UNSIGNED DEFAULT '0' NOT NULL,
    tx_begroupsroles_limit tinyint(4) UNSIGNED DEFAULT '0' NOT NULL,
    tx_begroupsroles_groups text
);
