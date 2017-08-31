<?php

namespace JasonRoman\NbaApi\Request\Stats\Data;

use JasonRoman\NbaApi\Client\Stats\StatsDataClient;
use JasonRoman\NbaApi\Request\Stats\AbstractStatsRequest;

abstract class AbstractStatsDataRequest extends AbstractStatsRequest
{
    const CLIENT = StatsDataClient::class;
}