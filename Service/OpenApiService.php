<?php declare (strict_types = 1);

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) Sven Roodbol <roodbol.sven@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SRedbull\ApiDocBundle\Service;

use SRedbull\ApiDocBundle\OpenApi\Exception\InvalidOpenApiSpecificationException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class OpenApiService
 */
class OpenApiService
{

    /**
     * The kernel interface.
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * SwaggerUiController constructor.
     *
     * @param KernelInterface $kernel The kernel interface.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Get the Open Api specification.
     *
     * @return \stdClass
     *
     * @throws InvalidOpenApiSpecificationException When the Open Api specification is invalid.
     */
    public function getSpec(): \stdClass
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $input = new ArrayInput(array(
            'command' => 'oa:generate',
        ));

        $output = new BufferedOutput();

        try {
            $application->run($input, $output);
        } catch (\Throwable $exception) {
            throw new InvalidOpenApiSpecificationException($exception->getMessage());
        }

        return \json_decode($output->fetch());
    }

}
