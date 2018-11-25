<?php declare(strict_types=1);
namespace Mfn\PHP\Analyzer\Report;

/**
 * A report always returns a string; subclass it to attach more information.
 */
abstract class Report
{

    /** @var NULL|SourceFragment */
    protected $sourceFragment;

    public function getSourceFragment(): ?SourceFragment
    {
        return $this->sourceFragment;
    }

    public function setSourceFragment(SourceFragment $sourceFragment): Report
    {
        $this->sourceFragment = $sourceFragment;
        return $this;
    }

    /**
     * Return an array with serialize discrete values
     *
     * If you subclass Report and want to add your own data to the serializer,
     * it's recommended to create your data in an array and union it with the
     * parent to chain in the parents definitions but don't override your own.
     *
     * Example:
     *    public function toArray() {
     *      return [
     *        'more' => 'data',
     *      ] + parent::toArray();
     *    }
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'kind' => get_class($this),
            'report' => $this->report(),
        ];
        if (null !== $this->sourceFragment) {
            $data['sourceFragment'] = $this->sourceFragment->toArray();
        }
        return $data;
    }

    abstract public function report(): string;
}
