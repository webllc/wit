<?php

namespace Webllc\Wit;

use Webllc\Wit\Model\EntityValue;
use Webllc\Wit\Model\Entity;

class IntentApi
{
    use ResponseHandler;

    /**
     * @var Client
     */
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * https://wit.ai/docs/http/20240304/#get__intents_link
     * https://wit.ai/docs/http/20240304/#get__intents__intent_link
     * @param string|null $intents
     * @return mixed
     */
    public function get(string $intents=null): mixed
    {
        $url="/intents";
        if ($intents) {
            $url.="/".$intents;
        }
        $response = $this->client->get($url);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param EntityValue[] $entityValues
     * @param null|string $description
     * @param null|string $newId
     *
     * @return mixed
     */
    public function update($entityId, array $entityValues = [], $description = null, $newId = null): mixed
    {
        $data = [];

        if (!empty($entityValues)) {
            $data['values'] = $entityValues;
        }

        if (!empty($description)) {
            $data['doc'] = $description;
        }

        if (!empty($newId)) {
            $data['id'] = $newId;
        }

        $response = $this->client->put(sprintf('/entities/%s', $entityId), $data);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     *
     * @return mixed
     */
    public function delete($entityId): mixed
    {
        $response = $this->client->delete(sprintf('/entities/%s', $entityId));

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param string $entityValue
     * @return mixed
     */
    public function deleteValue($entityId, $entityValue): mixed
    {
        $response = $this->client->delete(sprintf('/entities/%s/values/%s', $entityId, $entityValue));

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param EntityValue $entityValue
     *
     * @return mixed
     */
    public function addValue($entityId, EntityValue $entityValue): mixed
    {
        $response = $this->client->send('POST', sprintf('/entities/%s/values', $entityId), $entityValue);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param string $entityValue
     * @param string $expression
     *
     * @return mixed
     */
    public function addExpression($entityId, $entityValue, $expression): mixed
    {
        $response = $this->client->post(sprintf('/entities/%s/values/%s/expressions', $entityId, $entityValue), [
            'expression' => $expression,
        ]);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param string $entityValue
     * @param string $expression
     *
     * @return mixed
     */
    public function deleteExpression($entityId, $entityValue, $expression): mixed
    {
        $response = $this->client->delete(sprintf('/entities/%s/values/%s/expressions/%s', $entityId, $entityValue, $expression));

        return $this->decodeResponse($response);
    }

    /**
     * https://wit.ai/docs/http/20240304/#post__intents_link
     * @param array $data
     * @return mixed
     */
    public function create($data): mixed
    {

        $response = $this->client->post('/intents', $data);

        return $this->decodeResponse($response);
    }
}
