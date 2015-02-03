<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class SampleInstanceTemplateResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("sample_instance_template")
     */
    protected $data;
}
