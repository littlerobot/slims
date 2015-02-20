<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add relationship between {@see SampleTypeAttribute} and {@see SampleType}
 */
class Version20150215150219 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE sample_type_attribute ADD sample_type_attribute_id INT DEFAULT NULL');
        $this->addSql('
            ALTER TABLE sample_type_attribute
              ADD CONSTRAINT FK_2D88F7F06B1BCAA5
                FOREIGN KEY (sample_type_attribute_id)
                REFERENCES sample_type_template_attribute (id)
        ');
        $this->addSql('CREATE INDEX IDX_2D88F7F06B1BCAA5 ON sample_type_attribute (sample_type_attribute_id)');
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE sample_type_attribute DROP FOREIGN KEY FK_2D88F7F06B1BCAA5');
        $this->addSql('DROP INDEX IDX_2D88F7F06B1BCAA5 ON sample_type_attribute');
        $this->addSql('ALTER TABLE sample_type_attribute DROP sample_type_attribute_id');
    }
}
