<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add state to {@see Sample}.
 */
class Version20150417175647 extends AbstractMigration
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

        $this->addSql('ALTER TABLE sample_instance_template_attribute DROP FOREIGN KEY FK_749C145B81B21623');
        $this->addSql('DROP INDEX idx_749c145b81b21623 ON sample_instance_template_attribute');
        $this->addSql('
          CREATE INDEX IDX_3146F53881B21623 ON sample_instance_template_attribute (sample_instance_template_id)
        ');
        $this->addSql('
          ALTER TABLE sample_instance_template_attribute
            ADD CONSTRAINT FK_749C145B81B21623
              FOREIGN KEY (sample_instance_template_id)
                REFERENCES sample_instance_template (id)
        ');
        $this->addSql('ALTER TABLE sample ADD state VARCHAR(7) NOT NULL');
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

        $this->addSql('ALTER TABLE sample DROP state');
        $this->addSql('ALTER TABLE sample_instance_template_attribute DROP FOREIGN KEY FK_3146F53881B21623');
        $this->addSql('DROP INDEX idx_3146f53881b21623 ON sample_instance_template_attribute');
        $this->addSql('
          CREATE INDEX IDX_749C145B81B21623 ON sample_instance_template_attribute (sample_instance_template_id)
        ');
        $this->addSql('
          ALTER TABLE sample_instance_template_attribute
            ADD CONSTRAINT FK_3146F53881B21623
              FOREIGN KEY (sample_instance_template_id)
                REFERENCES sample_instance_template (id)
        ');
    }
}
