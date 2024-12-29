<?php

namespace Webllc\Wit\Api;

use Webllc\Wit\ActionMapping;
use Webllc\Wit\Api;
use Webllc\Wit\Conversation as ConversationDelegate;
use Webllc\Wit\ConverseApi;
use Webllc\Wit\Model\Context;
use Webllc\Wit\Model\Step;

/**
 * @deprecated This class is deprecated as of 0.1 and will be removed in 1.0.
 */
class Conversation
{
    const MAX_STEPS_ITERATION = 5;

    /**
     * @var ConversationDelegate
     */
    private $conversation;

    public function __construct(Api $api, ActionMapping $actionMapping)
    {
        $converseApi = new ConverseApi($api->getClient());
        $this->conversation = new ConversationDelegate($converseApi, $actionMapping);
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
        return $this->conversation->converse($sessionId, $message, $context, $stepIteration);
    }
}
