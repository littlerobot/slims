<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create sample type template and attribute tables.
 */
class Version20150111194717 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
          CREATE TABLE sample_type_attribute (
            id INT AUTO_INCREMENT NOT NULL,
            parent_id INT DEFAULT NULL,
            sequence SMALLINT NOT NULL,
            `label` VARCHAR(255) NOT NULL,
            type VARCHAR(255) NOT NULL,
            options LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\',
            INDEX IDX_2D88F7F0727ACA70 (parent_id),
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
          CREATE TABLE sample_type_template (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
          ALTER TABLE sample_type_attribute
            ADD CONSTRAINT FK_2D88F7F0727ACA70 FOREIGN KEY (parent_id) REFERENCES sample_type_template (id)
        ');
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE sample_type_attribute DROP FOREIGN KEY FK_2D88F7F0727ACA70');
        $this->addSql('DROP TABLE sample_type_attribute');
        $this->addSql('DROP TABLE sample_type_template');
    }
}
