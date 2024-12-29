<?php

namespace Webllc\Wit;

use Webllc\Wit\Exception\BadResponseException;
use Webllc\Wit\Exception\ConversationException;
use Webllc\Wit\Exception\InvalidStepException;
use Webllc\Wit\Exception\MaxIterationException;
use Webllc\Wit\Model\Context;
use Webllc\Wit\Model\Step;
use Webllc\Wit\Model\Step\Action;
use Webllc\Wit\Model\Step\Merge;
use Webllc\Wit\Model\Step\Message;
use Webllc\Wit\Model\Step\Stop;

class Conversation
{
    const MAX_STEPS_ITERATION = 5;

    /**
     * @var ConverseApi
     */
    private $converseApi;

    /**
     * @var ActionMapping
     */
    private $actionMapping;

    public function __construct(ConverseApi $converseApi, ActionMapping $actionMapping)
    {
        $this->converseApi = $converseApi;
        $this->actionMapping = $actionMapping;
    }

    /**
     * @param string $sessionId
     * @param string|null $message
     * @param Context|null $context
     * @param int $stepIteration
     *
     * @return Context The new Context
     */
    public function converse($sessionId, $message = null, Context $context = null, $stepIteration = self::MAX_STEPS_ITERATION)
    {
        $context = (null !== $context) ? $context : new Context();

        if ($stepIteration <= 0) {
            $error = new MaxIterationException("Max iteration exceeded");
            $this->actionMapping->error($sessionId, $context, $error);

            return $context;
        }

        try {
            $step = $this->getNextStep($sessionId, $message, $context);
        } catch (\Exception $e) {
            // Trigger the error action
            $stepData = $e instanceof ConversationException ? $e->getStepData() : [];
            $this->actionMapping->error($sessionId, $context, $e, $stepData);

            return new Context();
        }

        return $this->performStep($sessionId, $step, $context, $stepIteration);
    }

    /**
     * @param string $sessionId
     * @param string|null $message
     * @param Context $context
     *
     * @return Step Step object
     *
     * @throws BadResponseException
     * @throws ConversationException
     */
    private function getNextStep($sessionId, $message, Context $context)
    {
        $stepData = $this->converseApi->converse($sessionId, $message, $context);

        if (null === $stepData) {
            $stepData = [];
        }

        if (isset($stepData['error'])) {
            throw new ConversationException($stepData['error'], $sessionId, $context, $stepData);
        }

        try {
            $step = StepFactory::create($stepData);
        } catch (InvalidStepException $e) {
            throw new ConversationException($e->getMessage(), $sessionId, $context, $e->getStepData());
        }

        return $step;
    }

    /**
     * @param $sessionId
     * @param Step $step
     * @param Context $context
     * @param int $currentIteration
     *
     * @return Context
     */
    private function performStep($sessionId, Step $step, Context $context, $currentIteration)
    {
        switch (true) {
            case $step instanceof Merge:
                $newContext = $this->actionMapping->merge($sessionId, $context, $step);
                $context = $this->converse($sessionId, null, $newContext, --$currentIteration);
                break;
            case $step instanceof Message:
                $this->actionMapping->say($sessionId, $context, $step);
                $context = $this->converse($sessionId, null, $context, --$currentIteration);
                break;
            case $step instanceof Action:
                $newContext = $this->actionMapping->action($sessionId, $context, $step);
                $context = $this->converse($sessionId, null, $newContext, --$currentIteration);
                break;
            case $step instanceof Stop:
                $this->actionMapping->stop($sessionId, $context, $step);
                break;
        }

        return $context;
    }
}
