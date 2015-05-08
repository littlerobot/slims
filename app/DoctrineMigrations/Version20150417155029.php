<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add sample type to {@see Sample}.
 */
class Version20150417155029 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE sample ADD sample_type_id INT NOT NULL');
        $this->addSql('UPDATE sample SET sample_type_id = 1');
        $this->addSql('ALTER TABLE sample ADD CONSTRAINT FK_F10B76C3D5064105 FOREIGN KEY (sample_type_id) REFERENCES sample_type (id)');
        $this->addSql('CREATE INDEX IDX_F10B76C3D5064105 ON sample (sample_type_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE sample DROP FOREIGN KEY FK_F10B76C3D5064105');
        $this->addSql('DROP INDEX IDX_F10B76C3D5064105 ON sample');
        $this->addSql('ALTER TABLE sample DROP sample_type_id');
    }
}
