<?php

namespace Cscr\SlimsApiBundle\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Generates a URL that can be used to download a document associated with a {@see SampleType}
 */
class SampleTypeDocumentAttributeUrlGenerator
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param int $id
     * @param string $filename
     * @return string
     */
    public function generateUrl($id, $filename)
    {
        if (!$filename) {
            throw new \InvalidArgumentException('Attribute must have a filename.');
        }

        return $this->router->generate('sample_type_attribute_download', [
            'id' => $id,
            'filename' => $filename,
        ]);
    }
}
