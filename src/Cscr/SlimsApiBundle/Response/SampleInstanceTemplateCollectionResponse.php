<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class SampleInstanceTemplateCollectionResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("sample_instance_templates")
     */
    protected $data;
}
