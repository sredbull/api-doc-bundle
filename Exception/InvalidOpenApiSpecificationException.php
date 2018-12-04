<?php declare (strict_types = 1);

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) Sven Roodbol <roodbol.sven@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SRedbull\ApiDocBundle\Exception;

/**
 * Class InvalidOpenApiSpecificationException.
 */
class InvalidOpenApiSpecificationException extends \Exception
{

    /**
     * InvalidOpenApiSpecificationException constructor.
     *
     * @param string  $message The exception message.
     * @param integer $code    The HTTP error code.
     */
    public function __construct(string $message = 'Invalid Open Api specification', int $code = 500)
    {
        parent::__construct($message, $code);
    }

}
