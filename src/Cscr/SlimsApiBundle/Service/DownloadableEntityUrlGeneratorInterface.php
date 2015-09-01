<?php

namespace Cscr\SlimsApiBundle\Service;

interface DownloadableEntityUrlGeneratorInterface
{
    /**
     * Generate a URL for the given ID and filename.
     *
     * @param int $id
     * @param string $filename
     * @return string
     */
    public function generateUrl($id, $filename);

    /**
     * Can the generator generate a URL for the specified class?
     *
     * @param string $namespace Full namespace for the class.
     * @return bool
     */
    public function canGenerateFor($namespace);
}
