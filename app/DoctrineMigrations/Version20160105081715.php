<?php

namespace Application\Migrations;

use Cscr\SlimsApiBundle\Entity\Container;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Add hierarchy column to {@see Container} to allow improved searching.
 */
class Version20160105081715 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE container ADD hierarchy VARCHAR(255) NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE container DROP hierarchy');
    }

    /**
     * Set the hierarchy as it will be blank at the moment.
     *
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $doctrine = $this->container->get('doctrine');
        $em = $doctrine->getManager();
        $containers = $doctrine->getRepository(Container::class);

        foreach ($containers->findAll() as $container) {
            // setName updates the hierarchy string.
            $container->setName($container->getName());
        }

        $em->flush();

        parent::postUp($schema);
    }


    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
