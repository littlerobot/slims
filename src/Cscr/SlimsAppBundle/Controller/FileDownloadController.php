<?php

namespace Cscr\SlimsAppBundle\Controller;

use Cscr\SlimsApiBundle\Entity\Downloadable;
use Cscr\SlimsApiBundle\Entity\SampleInstanceAttribute;
use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends Controller
{
    /**
     * @Route("download/sample-type-attributes/{id}/{filename}", methods={"GET"}, name="sample_type_attribute_download")
     *
     * @param SampleTypeAttribute $attribute
     *
     * @return BinaryFileResponse
     */
    public function downloadSampleTypeAttributeAction(SampleTypeAttribute $attribute)
    {
        if (!$attribute->getFilename()) {
            throw $this->createNotFoundException();
        }

        return $this->sendFile($attribute);
    }

    /**
     * @Route(
     *  "download/sample-instance-attributes/{id}/{filename}",
     *  methods={"GET"},
     *  name="sample_instance_attribute_download"
     * )
     *
     * @param SampleInstanceAttribute $attribute
     *
     * @return BinaryFileResponse
     */
    public function downloadSampleInstanceAttributeAction(SampleInstanceAttribute $attribute)
    {
        if (!$attribute->getFilename()) {
            throw $this->createNotFoundException();
        }

        return $this->sendFile($attribute);
    }

    /**
     * @param Downloadable $attribute
     *
     * @return BinaryFileResponse
     */
    private function sendFile(Downloadable $attribute)
    {
        $path = sprintf('%s/%s', sys_get_temp_dir(), $attribute->getFilename());
        (new Filesystem())->dumpFile($path, $attribute->getBinaryContent());

        $response = new BinaryFileResponse($path);
        $response->deleteFileAfterSend(true);

        return $response;
    }
}
