<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add foreign key from {@see Sample} to {@see SampleType}.
 */
class Version20150418152630 extends AbstractMigration
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

        $this->addSql('
          ALTER TABLE sample
            ADD CONSTRAINT FK_F10B76C3D5064105 FOREIGN KEY (sample_type_id) REFERENCES sample_type (id)
        ');
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
    }
}
