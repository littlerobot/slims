<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create {@see SampleInstanceAttribute}.
 */
class Version20150417183729 extends AbstractMigration
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
          CREATE TABLE sample_instance_attribute (
            id INT AUTO_INCREMENT NOT NULL,
            sample_id INT NOT NULL,
            sample_instance_template_attribute_id INT NOT NULL,
            value LONGTEXT DEFAULT NULL,
            filename VARCHAR(255) DEFAULT NULL,
            mime_type VARCHAR(255) DEFAULT NULL,
            INDEX IDX_749C145B1B1FEA20 (sample_id),
            INDEX IDX_749C145BBA4D8074 (sample_instance_template_attribute_id),
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
          ALTER TABLE sample_instance_attribute
            ADD CONSTRAINT FK_749C145B1B1FEA20
              FOREIGN KEY (sample_id)
                REFERENCES sample (id)
        ');
        $this->addSql('
          ALTER TABLE sample_instance_attribute
            ADD CONSTRAINT FK_749C145BBA4D8074
              FOREIGN KEY (sample_instance_template_attribute_id)
                REFERENCES sample_instance_template_attribute (id)
        ');
        $this->addSql('ALTER TABLE sample ADD sample_instance_template_id INT NOT NULL');
        $this->addSql('UPDATE sample SET sample_instance_template_id = (SELECT MIN(id) FROM sample_instance_template)');
        $this->addSql('
          ALTER TABLE sample
            ADD CONSTRAINT FK_F10B76C381B21623
              FOREIGN KEY (sample_instance_template_id)
                REFERENCES sample_instance_template (id)
        ');
        $this->addSql('CREATE INDEX IDX_F10B76C381B21623 ON sample (sample_instance_template_id)');
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

        $this->addSql('DROP TABLE sample_instance_attribute');
        $this->addSql('ALTER TABLE sample DROP FOREIGN KEY FK_F10B76C381B21623');
        $this->addSql('DROP INDEX IDX_F10B76C381B21623 ON sample');
        $this->addSql('ALTER TABLE sample DROP sample_instance_template_id');
    }
}
