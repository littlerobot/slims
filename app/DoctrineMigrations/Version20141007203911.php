<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create container table.
 */
class Version20141007203911 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            '
            CREATE TABLE container (
                id INT AUTO_INCREMENT NOT NULL,
                parent_id INT DEFAULT NULL,
                name VARCHAR(255) NOT NULL,
                research_group VARCHAR(255) DEFAULT NULL,
                rows INT NOT NULL,
                columns INT NOT NULL,
                stores VARCHAR(20) NOT NULL,
                comment LONGTEXT DEFAULT NULL,
                INDEX IDX_C7A2EC1B727ACA70 (parent_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
            '
        );
        $this->addSql(
            '
            ALTER TABLE container
                ADD CONSTRAINT FK_C7A2EC1B727ACA70 FOREIGN KEY (parent_id) REFERENCES container (id)
            '
        );
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1B727ACA70');
        $this->addSql('DROP TABLE container');
    }
}
