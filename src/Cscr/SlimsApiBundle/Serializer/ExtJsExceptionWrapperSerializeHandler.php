<?php

namespace Cscr\SlimsApiBundle\Serializer;

use FOS\RestBundle\Serializer\ExceptionWrapperSerializeHandler;
use FOS\RestBundle\Util\ExceptionWrapper;
use JMS\Serializer\Handler\SubscribingHandlerInterface;

class ExtJsExceptionWrapperSerializeHandler extends ExceptionWrapperSerializeHandler implements SubscribingHandlerInterface
{
    /**
     * Adds 'success': false element.
     *
     * @param  ExceptionWrapper $exceptionWrapper
     * @return array
     */
    protected function convertToArray(ExceptionWrapper $exceptionWrapper)
    {
        return array(
            'success' => false,
            'code'    => $exceptionWrapper->getCode(),
            'message' => $exceptionWrapper->getMessage(),
            'errors'  => $exceptionWrapper->getErrors(),
        );
    }
}
