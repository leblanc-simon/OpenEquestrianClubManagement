<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1362945897.
 * Generated on 2013-03-10 21:04:57 by leviathan
 */
class PropelMigration_1362945897
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

CREATE TABLE `allowed_lesson`
(
    `customer_id` INTEGER NOT NULL COMMENT \'Client concerné par les leçons autorisés\',
    `order_id` INTEGER NOT NULL COMMENT \'Commande ayant entraîner cette autorisation\',
    `quantity` INTEGER NOT NULL COMMENT \'Nombre de leçons autorisées\',
    `taken` INTEGER NOT NULL COMMENT \'Nombre de leçons prises\',
    `remaining` INTEGER NOT NULL COMMENT \'Nombre de leçons restantes\',
    `expiration_date` DATE COMMENT \'Date de péremption pour le nombre de leçons (null si pas de date de péremption)\',
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `FI__customer_allowed_lesson` (`customer_id`),
    INDEX `FI__order_allowed_lesson` (`order_id`),
    CONSTRAINT `Rel_customer_allowed_lesson`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `Rel_order_allowed_lesson`
        FOREIGN KEY (`order_id`)
        REFERENCES `order` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB COMMENT=\'Table de gestion des leçons autorisées\';

CREATE TABLE `lesson`
(
    `customer_id` INTEGER NOT NULL COMMENT \'Client concerné par la leçon\',
    `date` DATETIME NOT NULL COMMENT \'Date de la leçon\',
    `horse_id` INTEGER COMMENT \'Cheval pris pendant la leçon\',
    `description` TEXT COMMENT \'Description de la leçon\',
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `FI__customer_lesson` (`customer_id`),
    INDEX `FI__horse_lesson` (`horse_id`),
    CONSTRAINT `Rel_customer_lesson`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `Rel_horse_lesson`
        FOREIGN KEY (`horse_id`)
        REFERENCES `horse` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB COMMENT=\'Table de gestion des leçons\';

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

DROP TABLE IF EXISTS `allowed_lesson`;

DROP TABLE IF EXISTS `lesson`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}