<?php

declare(strict_types=1);

namespace App\Presentation\Response\Exception;

use App\Presentation\Response\Exception\PublicInfo\PublicMessage;

enum ErrorInfo: int implements PublicInfoProviderInterface
{
    #[PublicMessage('Unauthorized Account')]
    case ERR_UNAUTHORIZED = 401;

    #[PublicMessage('Internal Server Error')]
    case ERR_INTERNAL_ERROR = 500;

    #[PublicMessage('Service Unavailable')]
    case ERR_MAINTENANCE = 503;

    private function getAttribute(): PublicMessage
    {
        /**
         * Local identity map for Info metadata objects.
         *
         * @var array<non-empty-string, PublicMessage> $messages
         */
        static $messages = [];

        if (isset($messages[$this->name])) {
            return $messages[$this->name];
        }

        $attributes = (new \ReflectionEnumBackedCase(self::class, $this->name))
            ->getAttributes(PublicMessage::class);

        if (isset($attributes[0])) {
            return $messages[$this->name] = $attributes[0]->newInstance();
        }

        return $messages[$this->name] = new PublicMessage();
    }

    public function getCode(): int
    {
        return $this->value;
    }

    public function getMessage(): string
    {
        $info = $this->getAttribute();

        return $info->message;
    }
}
