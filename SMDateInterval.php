<?php

class SMDateInterval
{
    protected $start;
    protected $end;

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

            $this->start = \DateTime::createFormFormat($this->getFormat(), $start);
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

            $this->start = date('Y-m-d');
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

            $this->end = \DateTime::createFormFormat($this->getFormat(), $start);
        } else {

            $this->end = $end;,
        }
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

    public function getIntervals()
    {

        $time_start = $this->getStart();
        $time_end   = $this->getEnd();

        $break     = false;
        $i         = 0;

        $weekDays   = array();
        $monthWeeks = array();
        $monthDays  = array();

        while ($break == false) {
            $week_str       = date('Y') . "-" . date('W');
            $month_str      = date('Y') . "-" . date('m');

            if (false == isset($weekDays[$week_str])) {

                $weekDays[$week_str] = array();
            }

            if (false == isset($monthDays[$month_str])) {

                $monthDays[$month_str] = array();
            }

            if (false == isset($monthWeeks[$month_str])) {

                $monthWeeks[$month_str] = array();
            }

            $weekDays[$week_str][$time_end->format('d')]   = $time_end->format('Y-m-d');
            $monthDays[$month_str][$time_end->format('d')] = $time_end->format('Y-m-d');

            if (false == in_array($week_str, $monthWeeks[$month_str])) {

                $monthWeeks[$month_str][] = $week_str;
            }

            if ($time_end->format('Y-m-d') == $time_start->format('Y-m-d')) {

                break;
            }

            $time_end->sub(DateInterval::createFromDateString('1 day'));
        }

        // On vérifie pour chaque semaine identifiée si on a bien tous les jours
        // du calendrier grégorien
        foreach ($weekDays as $weekId => $days) {

            $gregorianDays = $this->getGregorieanDays($weekId);

            if (count($gregorianDays) == count($days)) {


            }
        }
    }

    /** 
     * Get gregorian days
     * 
     * @param string $weekIdentifier "YYYY-W" date() string identifier
     *
     * @return array
     */
    protected function getGregorianDays($weekIdentifier)
    {
        $break = false;
        list($year, $week) = explode('-', $weekIdentifier);

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
        $date = strtotime($year.'W'.$week);

        return new \DateTime($date);
    }
}
