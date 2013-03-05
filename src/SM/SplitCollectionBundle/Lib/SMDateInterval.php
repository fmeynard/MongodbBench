<?php
namespace SM\SplitCollectionBundle\Lib;


/**
 * SMDateInterval
 *
 * @Description : Calcul les differents time ranges disponibles entre deux dates ( mois et semaines )
 * @Exemple : print_r(SMdateInterval::getTimeRanges('2013-01-01','2013-02-12','Y-m-d'));
 */
class SMDateInterval
{
    protected $start;
    protected $end;

    protected $months = array();
    protected $weeks  = array();
    protected $days   = array();

    /**
     * Object constructor
     *
     * @param string|\DateTime $start
     * @param string|\DateTime $end
     * @param string           $format 
     */
    public function __construct($start = null, $end = null, $format = null)
    {

        if (null != $start) {

            $this->setStart($start);
        }

        if (null != $end) {

            $this->setEnd($end);
        }

        if (null != $format) {

            $this->setFormat($format);
        }
    }

    /**
     * Setter : start
     *
     * @param string|DateTime $start Start date string or DateTime object
     *
     * @return SMDateInterval
     */
    public function setStart($start)
    {

        if (!($start instanceof DateTime)) {

            $this->start = \DateTime::createFromFormat($this->getFormat(), $start);
        } else {

            $this->start = $start;
        }

        return $this;
    }

    /** 
     * Getter : start
     *
     * @return DateTime
     */
    public function getStart()
    {

        if (false == isset($this->start)) {

            $this->start = new \DateTime();
        }

        return $this->start;
    }

    /**
     * Setter : end
     *
     * @param string|DateTime $end End date string or DateTime object
     *
     * @return SMDateInterval
     */
    public function setEnd($end)
    {

        if (!($end instanceof \DateTime)) {

            $this->end = \DateTime::createFromFormat($this->getFormat(), $end);
        } else {

            $this->end = $end;
        }

        return $this;
    }

    public function getEnd()
    {

        if (false == isset($this->end)) {

            $this->end = new \DateTime();
        }

        return $this->end;
    }

    /**
     * Setter : format 
     *
     * @param string $format
     *
     * @return SMDateInterval
     */
    public function setFormat($format)
    {

        $this->format = $format;

        return $this;
    }

    /** 
     * Getter : format
     *
     * @return String 
     */
    public function getFormat()
    {

        if (false == isset($this->format)) {

            $this->format = 'Y-m-d';
        }

        return $this->format;
    }

    /**
     * Process time ranges filtering
     *
     */
    public function process()
    {

        $time_start = $this->getStart();
        $time_end   = $this->getEnd();

        $break     = false;
        $i         = 0;

        $weekDays   = array();
        $monthWeeks = array();
        $monthDays  = array();
        $weekMonths = array();

        while ($break == false) {
            $week_str       = $time_end->format('Y') . "-" . $time_end->format('W');
            $month_str      = $time_end->format('Y') . "-" . $time_end->format('m');

            if (false == isset($weekDays[$week_str])) {

                $weekDays[$week_str] = array();
            }

            if (false == isset($monthDays[$month_str])) {

                $monthDays[$month_str] = array();
            }

            if (false == isset($monthWeeks[$month_str])) {

                $monthWeeks[$month_str] = array();
            }

            if (false == isset($weekMonths[$week_str])) {

                $weekMonths[$week_str] = array();
            }

            $weekDays[$week_str][(int) $time_end->format('d')]   = clone $time_end;
            $monthDays[$month_str][(int) $time_end->format('d')] = clone $time_end;

            if (false == in_array($week_str, $monthWeeks[$month_str])) {

                $monthWeeks[$month_str][] = $week_str;
            }

            if (false == in_array($month_str, $weekMonths[$week_str])) {

                $weekMonths[$week_str][] = $month_str;
            }

            if ($time_end->format('Y-m-d') == $time_start->format('Y-m-d')) {

                break;
            }

            $time_end->sub(\DateInterval::createFromDateString('1 day'));
        }


        // on vérifie pour chaque mois
        $_months = array_keys($monthDays);
        foreach ($_months as $month_str) {
            list($year, $month) = explode('-', $month_str);

            if (cal_days_in_month(CAL_GREGORIAN, $month, $year) == count($monthDays[$month_str])) {

                $this->addMonth($year, $month);

                // on vérifie pour chaque semaine si elle est présente sur
                // plusieurs mois : si oui on supprime que les jours du mois en
                // en cours, si non on supprime tous les jours de la semaine
                foreach ($monthWeeks[$month_str] as $week) {
                    if (1 < count($weekMonths[$week])) {

                        foreach($weekDays[$week] as $key => $currentWeekDay) {

                            if ($currentWeekDay->format('m') == $time_end->format('m')) {

                                unset($weekDays[$week][$key]);
                            }
                        }
                    } else {

                        unset($weekDays[$week]);
                    }
                }

                unset($monthWeeks[$month_str]);
            } 
        }

        // On vérifie pour chaque semaine identifiée si on a bien tous les jours
        // du calendrier grégorien
        foreach ($weekDays as $weekId => $days) {

            list($year, $week) = explode('-', $weekId);
            $gregorianDays = $this->getGregorianDays($year, $week);

            if (count($gregorianDays) == count($days)) {

                $this->addWeek($year, $week);
            } else {
                $this->addDays($weekDays[$weekId]);
            }
        }
    }

    /** 
     * Get gregorian days
     *
     * @param int $year
     * @param int $week
     *
     * @return array
     */
    protected function getGregorianDays($year, $week)
    {
        $break = false;

        // recup du first day de la week
        $day = $this->getWeekFirstDay($year,$week);
        $days = [];

        while(count($days) < 10) {

            if ($day->format('W') != $week) {

                return $days;
            }

            $days[] = $day->format('Y-m-d');

            $day->add(date_interval_create_from_date_string('1 day'));
        }

        throw new Exception('Something wrong happened =]');

        // boucle les jours en ajoutant 24h jsuqu'au changement de week
    }

    /**
     * Get first day of given week
     *
     * @param int $year Year number ( YYYY - format )
     * @param int $week Week identifier ( W : format )
     *
     * @return  \DateTime
     */
    protected function getWeekFirstDay($year, $week)
    {
        //trick to have timestamp from the first day of a given week
        $time = strtotime($year.'W'.$week);

        $obj = new \DateTime();
        $obj->setTimestamp($time);

        return $obj;
    }

    /**
     * Add month
     *
     * @param int $year
     * @param int $month
     *
     * @return SMDateInterval
     */
    protected function addMonth($year, $month)
    {

        $this->months[] = $year ."-". $month;
    }

    /**
     * Add week to returns
     *
     * @param int $year
     * @param int $week
     *
     * @return SMDateInterval
     */
    protected function addWeek($year, $week)
    {

        $this->weeks[] = $year ."-".$week;

        return $this;
    }

    /**
     * Add days to returns
     *
     * @param array $days Array of \DateTime
     *
     * @return SMDateInterval
     */
    protected function addDays(array $days)
    {
        $this->days = array_merge($this->days, $days);

        return $this;
    }

    /**
     * Get ranges
     *
     * @return array
     */
    public function getRanges()
    {

        return array(
            'months' => $this->getMonths(),
            'weeks'  => $this->getWeeks(),
            'days'   => $this->getDays(),
        );
    }

    /**
     * Get months 
     *
     * @return array
     */
    public function getMonths()
    {

        return $this->months;
    }

    /**
     * Get days
     *
     * @return array
     */
    public function getDays()
    {

        return $this->days;
    }

    /**
     * Get weeks
     *
     * @return array
     */
    public function getWeeks()
    {

        return $this->weeks;
    }

    /**
     * Get time ranges
     *
     * @param string|\DateTime $start
     * @param string|\DateTime $end
     * @param string           $format
     *
     * @return array
     */
    static public function getTimeRanges($start, $end, $format = null)
    {
        $instance = new self($start, $end, $format);
        $instance->process();

        return $instance->getRanges();
    }
}