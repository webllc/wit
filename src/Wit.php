<?php

namespace Garissman\Wit;

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

}
