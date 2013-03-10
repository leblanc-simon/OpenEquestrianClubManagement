<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1362922786.
 * Generated on 2013-03-10 14:39:46 by leviathan
 */
class PropelMigration_1362922786
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

ALTER TABLE `order`
    ADD `num_order` VARCHAR(255) NOT NULL COMMENT \'Numéro de la commande\' AFTER `customer_id`,
    ADD `vat` DOUBLE NOT NULL COMMENT \'Taux de TVA au moment de la commande\' AFTER `total`;

CREATE UNIQUE INDEX `index_order_num_order` ON `order` (`num_order`);

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

DROP INDEX `index_order_num_order` ON `order`;

ALTER TABLE `order` DROP `num_order`;

ALTER TABLE `order` DROP `vat`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}