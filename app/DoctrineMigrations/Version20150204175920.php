<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create the {@see SampleInstanceTemplateStoredAttribute} and {@see SampleInstanceTemplateRemovedAttribute} classes
 * and relations.
 */
class Version20150204175920 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
            CREATE TABLE sample_instance_attribute (
              id INT AUTO_INCREMENT NOT NULL,
              sample_instance_template_id INT DEFAULT NULL,
              sequence SMALLINT NOT NULL,
              `label` VARCHAR(255) NOT NULL,
              type VARCHAR(255) NOT NULL,
              options LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\',
              activity VARCHAR(255) NOT NULL,
              INDEX IDX_749C145B81B21623 (sample_instance_template_id),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE sample_instance_attribute
              ADD CONSTRAINT FK_749C145B81B21623
                FOREIGN KEY (sample_instance_template_id)
                  REFERENCES sample_instance_template (id)
        ');
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE sample_instance_attribute');
    }
}
