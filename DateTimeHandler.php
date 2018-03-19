<?php

namespace inquid\date_time;

use DateTime;
use Yii;

/**
 * Created by PhpStorm.
 * User: gogl92
 * Date: 14/03/17
 * Time: 09:51 AM
 */
class DateTimeHandler
{
    const ZONE = 'America/Monterrey';
    private $current_month;
    private $current_week;
    private $current_day;
    public function __construct()
    {
        $this->current_month = self::getDateTime('m');
        $this->current_week = self::getDateTime('w');
        $this->current_day = self::getDateTime('d');
    }
    /**
     * @param string en-US
     */
    public static function setYiiTimeZone($zone)
    {
        Yii::$app->formatter->locale = $zone;
    }
    /**
     * @return \DateTime
     */
    public static function currentDate()
    {
        return new \DateTime('now', new \DateTimeZone(DateTimeHandler::ZONE));
    }
    /**
     * @param $timeStamp
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function timeStampToString($timeStamp)
    {
        return Yii::$app->formatter->asTime($timeStamp);
    }
    /**
     * @param string $format
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function getYiiTime($format = 'yyyy-MM-dd')
    {
        return Yii::$app->formatter->asDate('now', $format);
    }
    /**
     * Return PHP's default Time Zone
     */
    public static function getDefautlTimeZone()
    {
        $timezone = date_default_timezone_get();
        echo $timezone . " ";
    }
    /**
     * @param $zone
     * America/Los_Angeles
     */
    public static function setDefaultTimeZone($zone)
    {
        date_default_timezone_set($zone);
    }
    /**
     * @param null $format
     * @return string
     */
    public static function getDateTime($format = null)
    {
        self::setDefaultTimeZone(DateTimeHandler::ZONE);
        if ($format == null)
            return self::currentDate()->format('Y-m-d H:i:s');
        return self::currentDate()->format($format);
    }
    /**
     * Return current date +1 day to avoid "ship date has passed
     * @return false|string
     */
    public static function getShipmentDate()
    {
        return date(DATE_ISO8601, strtotime(Date(DATE_ISO8601) . "+1 days"));
    }
    /**
     * @param $date string
     * @return string
     */
    public static function getWeekNumberByDate($date)
    {
        $resultDate = new DateTime($date);
        return $resultDate->format("W");
    }
    /**
     * @param $year
     * @param $week
     * @param $dayNumber
     * @return string
     */
    public static function getDateByNumber($year, $week, $dayNumber)
    {
        $gendate = new DateTime();
        $gendate->setISODate($year, $week, $dayNumber);
        return $gendate->format('Y-m-d');
    }
    /**
     * @param $date
     * @return false|string
     */
    public static function getOnlyDate($date)
    {
        if (isset($date))
            return self::formatDate($date, "d/m/Y");
        return '';
    }
    /**
     * @param $date
     * @param $format
     * @return false|string
     */
    public static function formatDate($date, $format)
    {
        return date($format, strtotime($date));
    }
    /**
     * @param null $month
     * @return array|bool
     */
    public function getMonthDays($month = null)
    {
        if (!isset($month)) {
            $month = $this->current_month;
        }
        return $month > 0 && $month <= 12 ? [date("Y-$month-01"), date("Y-$month-" . date("t"))] : false;
    }
    /**
     * @param $from
     * @param $to
     * @param string $date
     * @return bool
     */
    function dateIsBetween($from, $to, $date = 'now')
    {
        $date = is_int($date) ? $date : strtotime($date); // convert non timestamps
        $from = is_int($from) ? $from : strtotime($from); // ..
        $to = is_int($to) ? $to : strtotime($to);         // ..
        return ($date > $from) && ($date < $to); // extra parens for clarity
    }
    public function getWeekDays($week = null)
    {
        if (!isset($week)) {
            $week = $this->current_week;
        }
        return $week > 0 && $week <= 52 ? [date('Y-m-d', strtotime('-' . $week . ' days')), date('Y-m-d', strtotime('+' . (6 - $week) . ' days'))] : false;
    }


     * @return int
     */
    public static function now()
    {
        return time();
    }
    /**
     * @return string
     */
    public static function currentTime()
    {
        $format = 'Y-m-d H:i:s';
        return self::currentTimeWithFormat($format);
    }
    /**
     * @return string
     */
    public static function currentDate()
    {
        $format = 'Y-m-d';
        return self::currentTimeWithFormat($format);
    }
    /**
     * @param $format
     * @return string
     */
    public static function currentTimeWithFormat($format)
    {
        return self::timeWithFormat($format, self::now());
    }
    /**
     * @param string $format
     * @param string $time
     * @return string
     */
    public static function timeWithFormat($format, $time)
    {
        $date = gmdate($format, $time);
        if ($date === false) {
            return "";
        }
        return $date;
    }
    /**
     * @param $date_string
     * @param $day
     * @return string
     */
    public static function addDays($date_string, $day)
    {
        $format = 'Y-m-d H:i:s';
        return self::addDaysWithFormat($format, $date_string, $day);
    }
    /**
     * @param string $format
     * @param string $date_string
     * @param int $day
     * @return string
     */
    public static function addDaysWithFormat($format, $date_string, $day)
    {
        $seconds = (24 * 60 * 60) * $day;
        return self::addSecondsWithFormat($format, $date_string, $seconds);
    }
    /**
     * @param string $format
     * @param string $date_string
     * @param int $seconds
     * @return string
     */
    public static function addSecondsWithFormat($format, $date_string, $seconds)
    {
        $unix_time = self::strToTimeUTC($date_string);
        $unix_time += $seconds;
        return self::timeWithFormat($format, $unix_time);
    }
    /**
     * @param string $format
     * @param float $seconds
     * @return string
     */
    public static function afterSecondsFromNowWithFormat($format, $seconds)
    {
        return self::addSecondsWithFormat($format, self::currentTime(), $seconds);
    }
    /**
     * @param string $date_string
     * @return string
     */
    public static function getPreviousDay($date_string)
    {
        $format = 'Y-m-d H:i:s';
        return self::getPreviousDayWithFormat($format, $date_string);
    }
    /**
     * @param string $format
     * @param string $date_string
     * @return string
     */
    public static function getPreviousDayWithFormat($format, $date_string)
    {
        return self::addDaysWithFormat($format, $date_string, -1);
    }
    /**
     * @param string $date_string
     * @return string
     */
    public static function getNextDay($date_string)
    {
        $format = 'Y-m-d H:i:s';
        return self::getNextDayWithFormat($format, $date_string);
    }
    /**
     * @param string $format
     * @param string $date_string
     * @return string
     */
    public static function getNextDayWithFormat($format, $date_string)
    {
        return self::addDaysWithFormat($format, $date_string, 1);
    }
    /**
     * @param string $date_string
     * @return bool
     */
    public static function isLaterThanNow($date_string)
    {
        $now = self::currentTime();
        return self::isLaterThan($date_string, $now);
    }
    /**
     * @param string $first_date_string
     * @param string $second_date_string
     * @return bool
     */
    public static function isLaterThan($first_date_string, $second_date_string)
    {
        return $first_date_string > $second_date_string;
    }
    /**
     * @param string $date_string
     * @return int
     */
    public static function remainingTimeUntil($date_string)
    {
        return self::strToTimeUTC($date_string) - self::now();
    }
    /**
     * @param string $date_string
     * @return int
     */
    public static function elapsedTimeSince($date_string)
    {
        return self::now() - self::strToTimeUTC($date_string);
    }
    /**
     * @param string $date_string
     * @return false|int
     */
    public static function strToTimeUTC($date_string)
    {
        return strtotime($date_string . " UTC");
    }
    /**
     * @param float $seconds
     * @return string
     */
    public static function afterSecondsFromNow($seconds)
    {
        $format = 'Y-m-d H:i:s';
        return self::afterSecondsFromNowWithFormat($format, $seconds);
    }
}
