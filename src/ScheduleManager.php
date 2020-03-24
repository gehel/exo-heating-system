<?php

/**
 * The system obtains temperature data from a remote source,
 * compares it with a given threshold and controls a remote heating
 * unit by switching it on and off. It does so only within a time
 * period configured on a remote service (or other source)
 *
 * This is purpose-built crap.
 */
class ScheduleManager
{
    /**
     * This method is the entry point into the code. You can assume that it is
     * called at regular interval with the appropriate parameters.
     */
    public static function manage(HeatingManagerImpl $hM, string $threshold): void
    {
        $t = self::stringFromURL("http://probe.home:9999/temp", 4);

        if (gettimeofday(true) > self::startHour() && gettimeofday(true) < self::endHour()) {
            $hM->manageHeating($t, $threshold, true);
        }
        if (gettimeofday(true) < self::startHour() || gettimeofday(true) > self::endHour()) {
            $hM->manageHeating($t, $threshold, false);
        }
    }

    private static function endHour(): float
    {
        floatval(self::stringFromURL("http://timer.home:9990/end", 5));
    }

    private static function stringFromURL(string $urlString, int $s)
    {
        $c = curl_init();

        curl_setopt($c, CURLOPT_URL, $urlString);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        $o = curl_exec($c);

        curl_close($c);

        return substr($o, 0, $s);
    }

    static function startHour(): float
    {
        floatval(self::stringFromURL("http://timer.home:9990/start", 5));
    }
}
