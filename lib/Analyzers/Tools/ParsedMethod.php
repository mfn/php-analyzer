<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

use Mfn\PHP\Analyzer\Util\ParserPrinter;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\PrettyPrinter\Standard;

class ParsedMethod implements GenericMethod, Parsed
{

    /** @var ClassMethod */
    private $method;
    /** @var Standard */
    private $printer;
    /** @var ParsedObject */
    private $object;

    /**
     * @param ParsedObject $object A Method always belongs to an object
     * @param ClassMethod $method
     */
    public function __construct(ParsedObject $object, ClassMethod $method)
    {
        $this->object = $object;
        $this->method = $method;
        $this->printer = new ParserPrinter();
    }

    public function getNormalizedName(): string
    {
        return strtolower($this->method->name);
    }

    /**
     * @return ClassMethod
     */
    public function getMethod(): ClassMethod
    {
        return $this->method;
    }

    /**
     * @return ParsedClass
     */
    public function getClass(): Class_
    {
        if ($this->object instanceof ParsedClass) {
            return $this->object;
        }
        throw new \RuntimeException('Method is not part of a class but of '
            . $this->object->getKind() . ' instead');
    }

    /**
     * @return ParsedInterface
     */
    public function getInterface(): Interface_
    {
        if ($this->object instanceof ParsedInterface) {
            return $this->object;
        }
        throw new \RuntimeException('Method is not part of an interface but of '
            . $this->object->getKind() . ' instead');
    }

    /**
     * @return string
     */
    public function getFullSignature(): string
    {
        return
            $this->printer->pModifiers($this->method)
            . 'function '
            . ($this->method->byRef ? '&' : '')
            . $this->getNameAndParamsSignature();
    }

    public function getNameAndParamsSignature()
    {
        return
            $this->method->name
            . '(' . $this->printer->expose_pImplode($this->method->params, ', ') . ')';
    }

    public function getName(): string
    {
        return $this->method->name;
    }

    public function getObject(): GenericObject
    {
        // TODO: Implement getObject() method.
    }
}
