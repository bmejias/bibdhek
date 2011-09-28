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
?>

