<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1362819462.
 * Generated on 2013-03-09 09:57:42 by leviathan
 */
class PropelMigration_1362819462
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'OpenEquestrianClubManagement' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP INDEX `index_customer_show_name` ON `customer`;

ALTER TABLE `customer`
    ADD `phones` TEXT COMMENT \'Numéros de téléphone du client (multiples dans un stdClass)\' AFTER `email`,
    ADD `slug` VARCHAR(255) AFTER `updated_at`;

CREATE UNIQUE INDEX `customer_slug` ON `customer` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'OpenEquestrianClubManagement' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP INDEX `customer_slug` ON `customer`;

ALTER TABLE `customer` DROP `phones`;

ALTER TABLE `customer` DROP `slug`;

CREATE BTREE INDEX `index_customer_show_name` ON `customer` (`show_name`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}