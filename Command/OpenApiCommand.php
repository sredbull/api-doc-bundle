<?php declare (strict_types = 1);

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) Sven Roodbol <roodbol.sven@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SRedbull\ApiDocBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class OpenApiCommand
 */
class OpenApiCommand extends Command
{

    /**
     * The allowed generated formats.
     *
     * @var array
     */
    public static $allowedFormats = [
        'json',
        'yaml',
    ];

    /**
     * The kernel interface.
     *
     * @var KernelInterface $kernel
     */
    private $kernel;

    /**
     * OpenApiCommand constructor.
     *
     * @param KernelInterface $kernel The kernel interface.
     * @param string|null     $name   The name of the command.
     */
    public function __construct(KernelInterface $kernel, ?string $name = null)
    {
        parent::__construct($name);
        $this->kernel = $kernel;
    }

    /**
     * Configure the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('oa:generate')
            ->setDescription('Generate the Open Api docs.')
            ->setHelp('This command allows you to generate the Open Api docs in json or yaml.')
            ->addOption(
                'format',
                'f',
                InputArgument::OPTIONAL,
                'The output format "json" or "yaml"',
                'json'
            )
        ;
    }

    /**
     * Execute the command.
     *
     * @param InputInterface  $input  The input interface.
     * @param OutputInterface $output The output interface.
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $openApi = \OpenApi\scan($this->kernel->getRootDir());
        $format = $input->getOption('format');

        if (\in_array($format, self::$allowedFormats, true) === false) {
            $output->writeln(sprintf('<error>Option --format (-f) has a wrong format: "%s".</error>', $format));
        }

        if ($format === 'json') {
            $output->writeln($openApi->toJson());
        }

        if ($format === 'yaml') {
            $output->writeln($openApi->toYaml());
        }
    }

}
