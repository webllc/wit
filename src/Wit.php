<?php

namespace Webllc\Wit;

class Wit
{
    public function __construct(private readonly Client $client)
    {

    }
    public function utterance(): UtteranceApi
    {
        return new UtteranceApi($this->client);
    }

    public function intent(): IntentApi
    {
        return new IntentApi($this->client);
    }

    public function entity(): EntityApi
    {
        return new EntityApi($this->client);
    }

    public function message(): MessageApi
    {
        return new MessageApi($this->client);
    }

}
