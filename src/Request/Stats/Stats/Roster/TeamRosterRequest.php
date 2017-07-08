<?php

namespace JasonRoman\NbaApi\Request\Stats\Playoffs;

use Symfony\Component\Validator\Constraints as Assert;
use JasonRoman\NbaApi\Constraints as ApiAssert;
use JasonRoman\NbaApi\Params\LeagueIdParam;
use JasonRoman\NbaApi\Params\SeasonParam;
use JasonRoman\NbaApi\Params\TeamIdParam;
use JasonRoman\NbaApi\Request\AbstractStatsRequest;

/**
 * Get the players and coaches for a team for a given season. Supports both NBA and G-League.
 */
class TeamRosterRequest extends AbstractStatsRequest
{
    const ENDPOINT = '/stats/commonteamroster';

    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @ApiAssert\ApiRegex(pattern = SeasonParam::FORMAT)
     *
     * @var string
     */
    public $season;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("int")
     * @Assert\Range(min = TeamIdParam::MIN_VALUE, max = TeamIdParam::MAX_VALUE)
     *
     * @var int
     */
    public $teamId;

    /**
     * @Assert\Type("string")
     * @ApiAssert\ApiRegex(pattern = LeagueIdParam::OPTIONS_NBA_G_LEAGUE)
     *
     * @var string
     */
    public $leagueId;

}