<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create {@see SampleType} and {@see SampleTypeAttribute} tables.
 */
class Version20150215115422 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
            CREATE TABLE sample_type (
              id INT AUTO_INCREMENT NOT NULL,
              sample_type_template_id INT DEFAULT NULL,
              name VARCHAR(255) NOT NULL,
              INDEX IDX_30E87564F54299D7 (sample_type_template_id),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE sample_type_attribute (
              id INT AUTO_INCREMENT NOT NULL,
              sample_type_id INT DEFAULT NULL,
              value LONGTEXT DEFAULT NULL,
              filename VARCHAR(255) DEFAULT NULL,
              mime_type VARCHAR(255) DEFAULT NULL,
              INDEX IDX_2D88F7F0D5064105 (sample_type_id),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE sample_type
              ADD CONSTRAINT FK_30E87564F54299D7
                FOREIGN KEY (sample_type_template_id)
                REFERENCES sample_type_template (id)
        ');
        $this->addSql('
            ALTER TABLE sample_type_attribute
              ADD CONSTRAINT FK_2D88F7F0D5064105
                FOREIGN KEY (sample_type_id)
                REFERENCES sample_type (id)
            ');
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE sample_type_attribute DROP FOREIGN KEY FK_2D88F7F0D5064105');
        $this->addSql('DROP TABLE sample_type');
        $this->addSql('DROP TABLE sample_type_attribute');
    }
}
