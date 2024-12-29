<?php

namespace Webllc\Wit;

use Webllc\Wit\Model\Context;

class MessageApi
{
    use ResponseHandler;


    public function __construct(private readonly Client $client)
    {

    }

    /**
     * https://wit.ai/docs/http/20240304/#get__message_link
     * @param string $text
     * @param Context|null $context
     * @param array $queryParams
     *
     * @return mixed
     */
    public function getIntent(string $text, Context $context = null, array $queryParams = []): mixed
    {
        $query = array_merge($queryParams, [
            'q' => $text,
        ]);

        if (null !== $context && !$context->isEmpty()) {
            $query['context'] = json_encode($context);
        }

        $response = $this->client->get('/message', $query);

        return $this->decodeResponse($response);
    }
}
