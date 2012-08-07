<?php

/**
 * float to string with two decimals
 * @param float $amount
 * @return number(4, 2)
 */
function toCurrency($amount)
{
    return number_format($amount, 2, ",", ".");
}

/* testing the functionality
echo "Tests<br/>";
$tests = array(3, 3.5, 3.66, 3.10, 3.00, .0, .00, .5, .66, 
    .0000000000000000006666, .066, .0066, .00066, 1234500.66, 123456);
foreach ($tests as $test)
{
    echo $test." becomes ".toCurrency($test)."<br/>";
}
 */

/**
 * from string number(4, 2) to float by replacing the ',' by a '.'. Warning,
 * all '.' are first remove, therefore, toNumber(0.50) would return 50 instead 
 * of 0.50.
 *
 * @param string $amount_str String in format number(4, 2), e.g 1.234,56
 * @return float
 */
function toNumber($amount_str)
{
    if (strpos($amount_str, '.') <= strpos($amount_str, ','))
    {
        $amount_str = preg_replace("/\./", "", $amount_str);
    }
    return floatval(preg_replace("/,/", ".", $amount_str));
}

/**
 * The function returns the no. of business days between two dates and it skips 
 * the holidays taken from
 * http://stackoverflow.com/questions/336127/calculate-business-days
 *
 * @param data $startDate
 * @param date $endDate
 * @param array $holidays In fortma 'Y-m-d', e.g. 1982-06-24
 * @return int Number of days
 */
function getWorkingDays($startDate, $endDate, $holidays)
{
    /* The total number of days between the two dates. We compute the no. of 
     * seconds and divide it to 60*60*24
     * We add one to inlude both dates in the interval.
     */
    $days = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    /* It will return 1 if it's Monday,.. ,7 for Sunday */
    $the_first_day_of_week = date("N", strtotime($startDate));
    $the_last_day_of_week = date("N", strtotime($endDate));

    /* The two can be equal in leap years when february has 29 days, the equal 
     * sign is added here. In the first case the whole interval is within a 
     * week, in the second case the interval falls in two weeks.
     */
    if ($the_first_day_of_week <= $the_last_day_of_week)
    {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week)
            $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) 
            $no_remaining_days--;
    }
    else
    {
        /* (edit by Tokes to fix an edge case where the start day was a Sunday
         * and the end day was NOT a Saturday)
         */
        /* the day of the week for start is later than the day of the week for end
         */
        if ($the_first_day_of_week == 7)
        {
            /* if the start date is a Sunday, then we definitely subtract 1 day */
            $no_remaining_days--;

            if ($the_last_day_of_week == 6)
            {
                /* if the end date is a Saturday, then we subtract another day */
                $no_remaining_days--;
            }
        }
        else
        {
            /* the start date was a Saturday (or earlier), and the end date was 
             * (Mon..Fri) so we skip an entire weekend and subtract 2 days
             */
            $no_remaining_days -= 2;
        }
    }

    /* The no. of business days is:
     *
     * (number of weeks between the two dates) * (5 working days) + the 
     * remainder
     *
     * february in none leap years gave a remainder of 0 but still calculated 
     * weekends between first and last day, this is one way to fix it
     */
   $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }

    /* We subtract the holidays */
    foreach($holidays as $holiday)
    {
        $time_stamp = strtotime($holiday);
        /* If the holiday doesn't fall in weekend */
        if (strtotime($startDate) <= $time_stamp
            && $time_stamp <= strtotime($endDate)
            && date("N",$time_stamp) != 6 
            && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}

/* Example:
 * $holidays=array("2008-12-25","2008-12-26","2009-01-01");
 * echo getWorkingDays("2008-12-22","2009-01-02",$holidays)
 * => will return 7
 */

?>

