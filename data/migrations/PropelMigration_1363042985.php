<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1363042985.
 * Generated on 2013-03-12 00:03:05 by leviathan
 */
class PropelMigration_1363042985
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

ALTER TABLE `order_detail`
    ADD `card_id` INTEGER COMMENT \'Carte commandée\' AFTER `order_id`;

CREATE INDEX `FI__card_order_detail` ON `order_detail` (`card_id`);

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

ALTER TABLE `order_detail` DROP FOREIGN KEY `Rel_card_order_detail`;

DROP INDEX `FI__card_order_detail` ON `order_detail`;

ALTER TABLE `order_detail` DROP `card_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}