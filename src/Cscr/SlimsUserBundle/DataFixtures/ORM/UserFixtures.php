<?php

namespace Cscr\SlimsUserBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class UserFixtures extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return array(
            __DIR__ . '/Fixtures/users.yml',
        );
    }
}
