<?php declare (strict_types = 1);

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) Sven Roodbol <roodbol.sven@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SRedbull\ApiDocBundle\OpenApi;

use OpenApi\Analysis;
use OpenApi\Annotations\AbstractAnnotation;
use Symfony\Component\PropertyAccess\PropertyAccess;

class VariableProcessor
{

    /**
     * The variables.
     *
     * @var array $variables
     */
    private $variables;

    /**
     * VariableProcessor constructor.
     *
     * @param array $variables The variables.
     */
    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }

    /**
     * Invoke the variable processor.
     *
     * @param Analysis $analysis The result of the analyser.
     *
     * @return void
     */
    public function __invoke(Analysis $analysis): void
    {
        /** @var AbstractAnnotation $annotation */
        foreach($analysis->annotations as $annotation) {
            $this->parseAnnotation($annotation);
        }
    }

    /**
     * Parse the annotation.
     *
     * @param AbstractAnnotation $annotation The annotation.
     *
     * @return void
     */
    private function parseAnnotation(AbstractAnnotation $annotation): void
    {
        foreach (\get_object_vars($annotation) as $property => $value) {
            if ($value instanceof AbstractAnnotation) {
                $this->parseAnnotation($value);
                continue;
            }

            if (\is_string($value) === false) {
                continue;
            }

            if ($this->isVariable($value) === false) {
                continue;
            }

            $annotation->$property = $this->parseValue($value);
        }
    }

    /**
     * Parse the value.
     *
     * @param string $value The value.
     *
     * @return string
     */
    private function parseValue(string $value): string
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $value = $propertyAccessor->getValue($this->variables, $this->getPropertyPath($value));

        return $value;
    }

    /**
     * Check if the given value is a variable.
     *
     * @param string $value The value.
     *
     * @return bool
     */
    private function isVariable(string $value): bool
    {
        return \preg_match('#\%(.*?)\%#', $value) === 1;
    }

    /**
     * Get the propery path for the given value.
     *
     * @param string $value The value.
     *
     * @return string
     */
    private function getPropertyPath(string $value): string
    {
        $value = \preg_replace('/%/', '[', $value, 1);
        $value = \preg_replace('/%/', ']', $value, 1);
        $value = \preg_replace('/[.]/', '][', $value);

        return $value;
    }

}
