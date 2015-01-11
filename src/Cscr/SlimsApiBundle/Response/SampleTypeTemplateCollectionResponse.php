<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class SampleTypeTemplateCollectionResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("sample_type_templates")
     */
    protected $data;
}
