<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create user table
 */
class Version20140921160719 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            '
                CREATE TABLE user (
                    id INT AUTO_INCREMENT NOT NULL,
                    username VARCHAR(10) NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    is_active TINYINT(1) NOT NULL,
                    UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
            '
        );
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE user');
    }
}
