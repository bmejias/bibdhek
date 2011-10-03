<?php

/* number(N,2) to string with two decimals */
function toCurrency($amount)
{
	$no_cents = $amount * 100;
	if ($no_cents % 100 == 0)
		return ($no_cents / 100).".00";
	elseif ($no_cents % 10 == 0)
		return ($no_cents / 100)."0";
	else
		return "".$amount;
}

/* testing the functionality

echo "Tests<br/>";
echo "3 becomes ".toCurrency(3)."<br/>";
echo "3.5 becomes ".toCurrency(3.5)."<br/>";
echo "3.66 becomes ".toCurrency(3.66)."<br/>";
echo "3.10 becomes ".toCurrency(3.10)."<br/>";
echo "3.00 becomes ".toCurrency(3.00)."<br/>";
echo ".0 becomes ".toCurrency(.0)."<br/>";
echo ".00 becomes ".toCurrency(.00)."<br/>";
echo ".5 becomes ".toCurrency(.5)."<br/>";
echo ".66 becomes ".toCurrency(.66)."<br/>";

*/

/* The function returns the no. of business days between two dates and it skips 
 * the holidays taken from
 * http://stackoverflow.com/questions/336127/calculate-business-days
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

