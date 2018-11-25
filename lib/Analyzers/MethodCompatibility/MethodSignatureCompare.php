<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\MethodCompatibility;

use Mfn\PHP\Analyzer\Analyzers\Tools\ParsedMethod;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Generates signature/string representation of the method which allows it
 * easier to be compared to other methods if their parameters, types and other
 * properties match (except literal names).
 */
class MethodSignatureCompare
{

    /** @var ParsedMethod */
    private $method;
    /** @var string */
    private $signature = '';

    public function __construct(ParsedMethod $method)
    {
        $this->method = $method;
        $this->signature = self::generateMethodSignature($method->getMethod());
    }

    /**
     * Generate a method signature for comparison with other methods for
     * compatibility. This means names of parameter types and other subtle things
     * are deliberately omitted from the signature.
     *
     * @param ClassMethod $method
     * @return string
     */
    public static function generateMethodSignature(ClassMethod $method): string
    {
        $params = [];
        foreach ($method->params as $param) {
            $params[] = self::generateParamSignature($param);
        }
        return join(', ', $params);
    }

    public static function generateParamSignature(Param $param): string
    {
        $names = [];
        if (isset($param->type)) {
            if ($param->type instanceof Name) {
                $names[] = $param->type->toString();
            } else {
                $names[] = $param->type;
            }
        }
        $names[] = $param->byRef ? '&$' : '$';
        if (isset($param->default)) {
            $names[] = '=';
        }
        return join(' ', $names);
    }

    /**
     * @return ParsedMethod
     */
    public function getMethod(): ParsedMethod
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }
}
