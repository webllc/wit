<?php

namespace Garissman\Wit\Api;

use Garissman\Wit\ActionMapping;
use Garissman\Wit\Api;
use Garissman\Wit\Conversation as ConversationDelegate;
use Garissman\Wit\ConverseApi;
use Garissman\Wit\Model\Context;
use Garissman\Wit\Model\Step;

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
