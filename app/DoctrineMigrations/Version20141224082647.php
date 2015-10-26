<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Change container rows and columns to be smallints.
 */
class Version20141224082647 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
          ALTER TABLE container
            CHANGE rows rows SMALLINT NOT NULL,
            CHANGE columns columns SMALLINT NOT NULL
        ');
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
          ALTER TABLE container
            CHANGE rows rows INT NOT NULL,
            CHANGE columns columns INT NOT NULL
        ');
    }
}
