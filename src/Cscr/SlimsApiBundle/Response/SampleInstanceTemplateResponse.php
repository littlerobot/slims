<?php

namespace Cscr\SlimsApiBundle\Response;

use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate;
use JMS\Serializer\Annotation as JMS;

class SampleInstanceTemplateResponse extends ExtJsResponse
{
    /**
     * @var SampleInstanceTemplate
     *
     * @JMS\SerializedName("sample_instance_template")
     */
    protected $data;

    /**
     * @param SampleInstanceTemplate $template
     */
    public function __construct(SampleInstanceTemplate $template)
    {
        parent::__construct($template);
    }
}
