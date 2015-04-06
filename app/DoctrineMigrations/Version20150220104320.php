<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150220104320 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE sample_type_attribute DROP FOREIGN KEY FK_2D88F7F0D5064105');
        $this->addSql('ALTER TABLE sample_type_attribute CHANGE sample_type_id sample_type_id INT NOT NULL');
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
        $this->addSql('ALTER TABLE sample_type_attribute CHANGE sample_type_id sample_type_id INT DEFAULT NULL');
        $this->addSql('
            ALTER TABLE sample_type_attribute
              ADD CONSTRAINT FK_2D88F7F0D5064105
                FOREIGN KEY (sample_type_id)
                REFERENCES sample_type (id)
        ');
    }
}
