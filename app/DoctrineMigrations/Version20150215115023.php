<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150215115023 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('RENAME TABLE sample_type_attribute TO sample_type_template_attribute');
        $this->addSql('CREATE INDEX IDX_E2F82A69727ACA70 ON sample_type_template_attribute (parent_id)');
        $this->addSql('DROP INDEX idx_2d88f7f0727aca70 ON sample_type_template_attribute');
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('RENAME TABLE sample_type_template_attribute TO sample_type_attribute');
        $this->addSql('CREATE INDEX IDX_2D88F7F0727ACA70 ON sample_type_template_attribute (parent_id)');
        $this->addSql('DROP INDEX idx_e2f82a69727aca70 ON sample_type_template_attribute');
    }
}
