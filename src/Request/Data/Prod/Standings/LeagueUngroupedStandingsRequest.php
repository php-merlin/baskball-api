<?php

namespace JasonRoman\NbaApi\Request\Data\Prod\Standings;

use JasonRoman\NbaApi\Request\AbstractDataRequest;

/**
 * Get the full, ungrouped league standings. This also contains sort keys.
 */
class LeagueUngroupedStandingsRequest extends AbstractDataRequest
{
    const ENDPOINT = '/data/prod/v1/current/standings_all.json';
}