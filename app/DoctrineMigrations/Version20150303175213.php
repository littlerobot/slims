<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Rename SampleTypeAttribute to SampleTypeTemplateAttribute
 */
class Version20150303175213 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        if (!$schema->hasTable('sample_type_template_attribute')) {
            $this->addSql('RENAME TABLE sample_type_attribute TO sample_type_template_attribute');
        }
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        if (!$schema->hasTable('sample_type_attribute')) {
            $this->addSql('RENAME TABLE sample_type_template_attribute TO sample_type_attribute');
        }
    }
}
