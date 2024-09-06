<?php

namespace Garissman\Wit;

use Garissman\Wit\Model\EntityValue;
use Garissman\Wit\Model\Entity;

class EntityApi
{
    use ResponseHandler;


    public function __construct(private readonly Client $client)
    {
    }

    /**
     * https://wit.ai/docs/http/20240304/#get__entities_link
     * https://wit.ai/docs/http/20240304/#get__entities__entity_link
     * @param string|null $entityId
     *
     * @return mixed
     */
    public function get(string $entityId = null): mixed
    {
        if (null !== $entityId) {
            $entityId = '/'.$entityId;
        }


        $response = $this->client->get(sprintf('/entities%s', $entityId));

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
    public function update($entityId, array $entityValues = [], $description = null, $newId = null)
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
    public function delete($entityId)
    {
        $response = $this->client->delete(sprintf('/entities/%s', $entityId));

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param string $entityValue
     * @return mixed
     */
    public function deleteValue($entityId, $entityValue)
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
    public function addValue($entityId, EntityValue $entityValue)
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
    public function addExpression($entityId, $entityValue, $expression)
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
    public function deleteExpression($entityId, $entityValue, $expression)
    {
        $response = $this->client->delete(sprintf('/entities/%s/values/%s/expressions/%s', $entityId, $entityValue, $expression));

        return $this->decodeResponse($response);
    }

    /**
     * @param $name
     * @param $roles
     * @param array $lookups
     * @param array $keywords
     * @return mixed
     */
    public function create($name, $roles, array $lookups = [], array $keywords=[]): mixed
    {
        $data = [
            'name' => $name,
            'roles' => $roles,
            'lookups' => $lookups,
            'keywords' => $keywords,
        ];

        $response = $this->client->post('/entities', $data);

        return $this->decodeResponse($response);
    }
}
