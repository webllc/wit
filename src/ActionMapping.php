<?php

namespace Webllc\Wit;

use Webllc\Wit\Model\Context;
use Webllc\Wit\Model\Step\Action;
use Webllc\Wit\Model\Step\Merge;
use Webllc\Wit\Model\Step\Message;
use Webllc\Wit\Model\Step\Stop;

abstract class ActionMapping
{
    /**
     * @param string $sessionId
     * @param Context $context
     * @param Action $step
     *
     * @return Context
     */
    abstract public function action($sessionId, Context $context, Action $step);

    /**
     * @param string $sessionId
     * @param Context $context
     * @param Message $context
     */
    abstract public function say($sessionId, Context $context, Message $step);

    /**
     * @param string $sessionId
     * @param Context $context
     * @param \Exception|string $error
     * @param array $stepData
     */
    abstract public function error($sessionId, Context $context, $error = 'Unknown Error', array $stepData = []);

    /**
     * @param string $sessionId
     * @param Context $context
     * @param Merge $step
     *
     * @return Context
     */
    abstract public function merge($sessionId, Context $context, Merge $step);

    /**
     * @param string $sessionId
     * @param Context $context
     * @param Stop $step
     */
    abstract public function stop($sessionId, Context $context, Stop $step);
}
