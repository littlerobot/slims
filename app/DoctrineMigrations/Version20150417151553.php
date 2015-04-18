<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create initial table for {@see Sample}.
 */
class Version20150417151553 extends AbstractMigration
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
          CREATE TABLE sample (
            id INT AUTO_INCREMENT NOT NULL,
            container_id INT DEFAULT NULL,
            row INT NOT NULL,
            `column` INT NOT NULL,
            INDEX IDX_F10B76C3BC21F742 (container_id),
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
          ALTER TABLE sample
            ADD CONSTRAINT FK_F10B76C3BC21F742
              FOREIGN KEY (container_id)
                REFERENCES container (id)
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

        $this->addSql('DROP TABLE sample');
    }
}
