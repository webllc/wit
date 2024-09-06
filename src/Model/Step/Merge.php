<?php

namespace Garissman\Wit\Model\Step;

use Garissman\Wit\Model\Step;

class Merge extends AbstractStep
{
    /**
     * @param array $entities
     * @param float $confidence
     */
    public function __construct(array $entities, $confidence)
    {
        parent::__construct(Step::TYPE_MERGE, $confidence, $entities);
    }
}
