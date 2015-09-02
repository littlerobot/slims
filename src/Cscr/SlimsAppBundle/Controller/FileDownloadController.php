<?php

namespace Cscr\SlimsAppBundle\Controller;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends Controller
{
    /**
     * @Route("download/{id}/{filename}", name="sample_type_attribute_download")
     *
     * @param SampleTypeAttribute $attribute
     *
     * @return BinaryFileResponse
     */
    public function downloadAction(SampleTypeAttribute $attribute)
    {
        if (!$attribute->getFilename()) {
            throw $this->createNotFoundException();
        }

        $path = tempnam(sys_get_temp_dir(), 'attribute');
        file_put_contents($path, $attribute->getBinaryContent());

        // FIXME: The file should be deleted after it has been sent.

        return new BinaryFileResponse($path);
    }
}
