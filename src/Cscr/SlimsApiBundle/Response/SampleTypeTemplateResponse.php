<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class SampleTypeTemplateResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("sample_type_template")
     */
    protected $data;
}
