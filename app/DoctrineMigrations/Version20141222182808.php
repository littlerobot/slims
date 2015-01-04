<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Change container research group from text to foreign key to research_group table.
 */
class Version20141222182808 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
          ALTER TABLE container
            ADD research_group_id INT DEFAULT NULL,
            DROP research_group
        ');
        $this->addSql('
          ALTER TABLE container
            ADD CONSTRAINT FK_C7A2EC1B3AF8E8D FOREIGN KEY (research_group_id) REFERENCES research_group (id)
        ');
        $this->addSql('CREATE INDEX IDX_C7A2EC1B3AF8E8D ON container (research_group_id)');
    }

    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1B3AF8E8D');
        $this->addSql('DROP INDEX IDX_C7A2EC1B3AF8E8D ON container');
        $this->addSql('
          ALTER TABLE container
            ADD research_group VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci,
            DROP research_group_id
        ');
    }
}
