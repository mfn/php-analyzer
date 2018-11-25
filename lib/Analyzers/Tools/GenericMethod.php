<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Analyzers\Tools;

interface GenericMethod
{

    public function getName(): string;

    /**
     * Usually lower cased (because PHP is case-insensitive) so names can be
     * easier compared.
     * @return string
     */
    public function getNormalizedName(): string;

    public function getObject(): GenericObject;

    public function getInterface(): Interface_;

    public function getClass(): Class_;
}
