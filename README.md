# Wit.ai PHP sdk

This is an unofficial php sdk for [Wit.ai][1] and it's still in progress...

```
Wit.ai: Easily create text or voice based bots that humans can chat with on their preferred messaging platform.
```

## Install:

Via composer:

```
$ composer require garissman/wit
```

## Usage:

Using the low level `Client`:

```php

require_once __DIR__.'/vendor/autoload.php';

use Garissman\Wit\Client;

$client = new Client('app_token');

$response = $client->get('/message', [
    'q' => 'Hello I live in London',
]);

// Get the decoded body
$intent = json_decode((string) $response->getBody(), true);

```

You can used the `Message` api class to extract meaning of a sentence:

```php

require_once __DIR__.'/vendor/autoload.php';

use Garissman\Wit\Client;
use Garissman\Wit\MessageApi;

$client = new Client('app_token');
$api = new MessageApi($client);

$meaning = $api->extractMeaning('Hello I live in London');

```

## Conversation

The `Conversation` class provides an easy way to use the `converse` api and execute automatically the chaining steps :

First, you need to create an `ActionMapping` class to customize the actions behavior.

```php

namespace Custom;

use Garissman\Wit\Model\Step\Action;
use Garissman\Wit\Model\Step\Message;

class MyActionMapping extends ActionMapping
{
    /**
     * @inheritdoc
     */
    public function action($sessionId, Context $context, Action $step)
    {
        return call_user_func_array(array($this, $step->getAction()), array($sessionId, $context));
    }

    /**
     * @inheritdoc
     */
    public function say($sessionId, Context $context, Message $step)
    {
        echo $step->getMessage();
    }

     ....
}

```

And using it in the `Conversation` class.

```php

require_once __DIR__.'/vendor/autoload.php';

use Garissman\Wit\Client;
use Garissman\Wit\ConverseApi;
use Garissman\Wit\Conversation;
use Custom\MyActionMapping;

$client = new Client('app_token');
$api = new ConverseApi($client);
$actionMapping = new MyActionMapping();
$conversation = new Conversation($api, $actionMapping);

$context = $conversation->converse('session_id', 'Hello I live in London');

```

`Conversation::converse()` return the last available `Context`.

[1]: https://wit.ai
