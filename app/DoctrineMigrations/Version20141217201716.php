<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Rename user table to slims_user and associate research_group.
 */
class Version20141217201716 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
          CREATE TABLE slims_user (
            id INT AUTO_INCREMENT NOT NULL,
            research_group_id INT DEFAULT NULL,
            username VARCHAR(10) NOT NULL,
            name VARCHAR(255) NOT NULL,
            is_active TINYINT(1) NOT NULL,
            UNIQUE INDEX UNIQ_FA449CC8F85E0677 (username),
            INDEX IDX_FA449CC83AF8E8D (research_group_id),
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE slims_user
              ADD CONSTRAINT FK_FA449CC83AF8E8D FOREIGN KEY (research_group_id) REFERENCES research_group (id)
        ');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE container CHANGE colour colour VARCHAR(6) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
          CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL,
            username VARCHAR(10) NOT NULL,
            name VARCHAR(255) NOT NULL,
            research_group VARCHAR(255) NOT NULL,
            is_active TINYINT(1) NOT NULL,
            UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('DROP TABLE slims_user');
        $this->addSql('ALTER TABLE container CHANGE colour colour VARCHAR(7) DEFAULT NULL');
    }
}
