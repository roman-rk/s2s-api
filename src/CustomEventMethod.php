<?php

declare(strict_types=1);

namespace Mycom\Tracker\S2S\Api;

use GuzzleHttp\RequestOptions;
use Mycom\Tracker\S2S\Api\Client\{ClientInterface, Method, MethodInterface};
use Mycom\Tracker\S2S\Api\Common\Credentials;
use Mycom\Tracker\S2S\Api\CustomEventMethod\{Params, ParamsInterface, ParamsValidator};

/**
 * Custom event command implementation
 */
final class CustomEventMethod extends Method implements MethodInterface
{
    /** @var string Custom event command name */
    private static $URI = 'customEvent';

    /** @var Credentials */
    private $credentials;

    /** @var int */
    private $idApp;

    /** @var ParamsInterface */
    private $params;

    /** @var ParamsValidator */
    private $validator;

    /**
     * CustomEvent constructor.
     *
     * @param Credentials $credentials
     * @param int $idApp
     */
    public function __construct(Credentials $credentials, int $idApp)
    {
        parent::__construct(self::$URI);

        $this->credentials = $credentials;
        $this->idApp = $idApp;
        $this->params = new Params();
        $this->validator = new ParamsValidator($this->params);
    }

    /** @inheritDoc */
    public function validate()
    {
        $this->validator->validateCustomEventNameRequired();
    }

    /**
     * Return event params object
     *
     * @return ParamsInterface
     */
    public function params(): ParamsInterface
    {
        return $this->params;
    }

    /** @inheritDoc */
    public function getRequestOptions(): array
    {
        return [
            RequestOptions::HEADERS => [
                ClientInterface::AUTH_HEADER_NAME => $this->credentials->getToken(),
            ],
            RequestOptions::QUERY => [
                'idApp' => $this->idApp,
            ],
            RequestOptions::JSON => $this->params->toArray(),
        ];
    }
}
