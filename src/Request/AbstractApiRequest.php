<?php

namespace JasonRoman\NbaApi\Request;

use JasonRoman\NbaApi\Client\AbstractApiClient;
use JasonRoman\NbaApi\Response\ResponseType;

/**
 * Base class which any API-specific requests must extend from (from api.nba.net)
 */
abstract class AbstractApiRequest extends AbstractNbaApiRequest
{
    const BASE_URI = AbstractApiClient::BASE_URI;
    const CLIENT   = AbstractApiClient::class;

    // default response type for most requests - override for non-JSON requests
    const DEFAULT_RESPONSE_TYPE = ResponseType::JSON;
}