<?php

namespace inquid\date_time;

use Carbon\Carbon;
use DateTime;
use Yii;


/**
 * Created by PhpStorm.
 * User: gogl92
 * Date: 14/03/17
 * Time: 09:51 AM
 */
class DateTimeHandler extends Carbon
{
    const ZONE = 'America/Monterrey';
    private $current_month;
    private $current_week;
    private $current_day;

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
        return Carbon::now(DateTimeHandler::ZONE);
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
        $date = self::setDefaultTimeZone(DateTimeHandler::ZONE);
        if ($format == null)
            return self::currentDate()->format('Y-m-d H:i:s');
        return self::currentDate()->format($format);
    }

    /**
     * @param null $format
     * @return string
     */
    public static function getDateTimeMinusDays($date, $minus)
    {
        self::setDefaultTimeZone(DateTimeHandler::ZONE);
        return date('Y-m-d', strtotime("-{$minus} day", strtotime($date)));
    }

    /**
     * @param null $format
     * @return string
     */
    public static function getDateTimePlusDays($format = null, $plus)
    {
        self::setDefaultTimeZone(DateTimeHandler::ZONE);
        if ($format == null)
            return date('Y-m-d H:i:s', strtotime("+$plus days"));
        return date($format, strtotime("+$plus days"));
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

    public static function getDatePlusDays($date, $plus)
    {
        self::setDefaultTimeZone(self::ZONE);
        return date('Y-m-d', strtotime("+{$plus} day", strtotime($date)));
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


    /**
     * Get the date of a exam 15 days from now
     * @return string
     */
    public function getTestDate()
    {
        $date = $this->addDays(16);
        if ($date->isSaturday()) {
            return $date->addDays(2);
        } elseif ($date->isSunday()) {
            return $date->addDay();
        }
        return $date;
    }

    /**
     * @param $date
     * @return bool
     */
    public static function isBeforeToday($date)
    {
        return self::getDateTime() > $date;
    }
}
