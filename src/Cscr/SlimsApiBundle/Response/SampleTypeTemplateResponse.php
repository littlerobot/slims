<?php

namespace Cscr\SlimsApiBundle\Response;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use JMS\Serializer\Annotation as JMS;

class SampleTypeTemplateResponse extends ExtJsResponse
{
    /**
     * @var SampleTypeTemplate
     *
     * @JMS\SerializedName("sample_type_template")
     */
    protected $data;

    public function __construct(SampleTypeTemplate $template)
    {
        parent::__construct($template);
    }
}
