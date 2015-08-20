<?php

namespace Cscr\SlimsApiBundle\Entity;

interface Downloadable
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @param string $url
     */
    public function setUrl($url);
}
