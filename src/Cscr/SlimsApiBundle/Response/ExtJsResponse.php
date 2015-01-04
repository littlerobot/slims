<?php

namespace Cscr\SlimsApiBundle\Response;

use JMS\Serializer\Annotation as JMS;

class ExtJsResponse
{
    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var mixed
     *
     * @JMS\SerializedName("data")
     */
    protected $data;

    public function __construct($data)
    {
        if ($data) {
            $this->success = true;
        }

        $this->data = $data;
    }
}
