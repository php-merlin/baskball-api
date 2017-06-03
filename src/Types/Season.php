<?php

namespace JasonRoman\NbaApi\Types;

class Season
{
    const FORMAT      = '/^\d{4}-\d{2}$/';
    const YEAR_FORMAT = '/^\d{4}$/';

    /**
     * @var string
     */
    public $value;

    /**
     * @param string $year
     * @return string
     */
    public static function fromYear($year)
    {
        if (preg_match(self::YEAR_FORMAT, $year) !== 1) {
            throw new \InvalidArgumentException(sprintf('Year must be in %s format', self::FORMAT));
        }

        return $year.'-'.substr($year + 1, -2);
    }

    /**
     * @return string
     */
    public static function currentSeason()
    {
        // if August or earlier, the season started from the previous year
        $yearStart = (date('n') < 9) ? date('Y') - 1 : date('Y');

        return self::fromYear($yearStart);
    }
}