<?php
/*
Plugin Name: TimeSlot
Plugin URI: http://marcusstenbeck.com/timeslot
Description: A booking system.
Version: 0.1
Author: Marcus Stenbeck
Author URI: http://marcusstenbeck.com
License: I don't know yet.
.
Why are other booking systems shit or crazy expensive?
.
*/

include 'TS_WordpressDatabaseConnector.php';
include 'TS_Resource.php';
include 'TS_User.php';
include 'TS_Booking.php';
include 'TS_CompositeSchedule.php';

class TimeSlot
{
	public function __construct()
	{
		add_shortcode('timeslot', array($this, 'shortcode'));
	}
	
	public function shortcode()
	{
		$user = new TS_User($GLOBALS['current_user']);
		$return = '<p>You are '.$user->getName().' ('.$user->getID().')</p>';
		if($_GET['action'] == 'book')
		{			
			$booking = new TS_Booking($_GET['uid'], $_GET['resource'], $_GET['timestamp'], 86400);
			
			if($_GET['uid'] != 0 && $booking->save())
				$return .= '<p>Din bokning genomfördes!</p>';
			else
				$return .= '<p>Din bokning misslyckades...</p>';
		}
		else if($_GET['action'] == 'unbook')
		{
			if(TS_Booking::delete($_GET['booking_id']))
				$return .= '<p>Din avbokning lyckades!</p>';
			else
				$return .= '<p>Din avbokning misslyckades...</p>';
		}
		else
		{
			$resources = TS_Resource::getResources();
			$return = $return.'<ul>';
			
			foreach($resources as $resource)
			{
				$return = $return.'<li><h3>'.$resource->getName().' ('.$resource->getID().')</h3>';
				$return = $return.'<ul>';
				
				$schedule = $resource->getSchedule();
				$bookings = $resource->getBookings();



/* ##########  DETTA ÄR OTROLIG FULKOD FÖR ATT VISA KALENDERN - MÅSTE ÄNDRAS!!! */			
/* CALENDAR CODE START */
				
				$month = 10;
				$year = 2011;
				
				/* draw table */
				$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
				
				/* table headings */
				$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
				$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
				
				/* days and weeks vars now ... */
				$running_day = date('w',mktime(0,0,0,$month,1,$year));
				$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
				$days_in_this_week = 1;
				$day_counter = 0;
				$dates_array = array();
				
				/* row for week one */
				$calendar.= '<tr class="calendar-row">';
				
				/* print "blank" days until the first of the current week */
				for($x = 0; $x < $running_day; $x++):
				  $calendar.= '<td class="calendar-day-np">&nbsp;</td>';
				  $days_in_this_week++;
				endfor;
				
				/* keep going with days.... */
				for($list_day = 1; $list_day <= $days_in_month; $list_day++):
					$available = $schedule->getAvailability(mktime(0,0,0,$month,$list_day,$year));
				    	
					$calendar.= '<td class="calendar-day'.($available ? ' available' : ' unavailable').'">';
					
					/* add in the day number */
					$calendar.= '<div class="day-number">'.$list_day.'</div>';
				
	/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
					if($available)
					{
						$booked = false;
						
						foreach($bookings as $booking)
							if($booking->isBooked(mktime(0,0,0,$month,$list_day,$year)))
							{
								$booked = $booking;
								break;
							}
						
						if($booked)
							if($booked->getUserID() == $user->getID())
								$calendar.= '<p><a href="'.curPageURL().'?action=unbook&booking_id='.$booked->getID().'">UNBOOK</a></p>';
							else
								$calendar.= '<p>BOOKED</p>';
						else
							$calendar.= '<p><a href="'.curPageURL().'?action=book&uid='.$user->getID().'&timestamp='.mktime(0,0,0,$month,$list_day,$year).'&resource='.$resource->getID().'">BOOK</a></p>';
					}
					else
						$calendar.= '<p>&nbsp;</p>';
				      
					$calendar.= '</td>';
					if($running_day == 6):
						$calendar.= '</tr>';
						if(($day_counter+1) != $days_in_month):
							$calendar.= '<tr class="calendar-row">';
						endif;
						$running_day = -1;
						$days_in_this_week = 0;
					endif;
					$days_in_this_week++; $running_day++; $day_counter++;
				endfor;
				
				/* finish the rest of the days in the week */
				if($days_in_this_week < 8):
					for($x = 1; $x <= (8 - $days_in_this_week); $x++):
						$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
					endfor;
				endif;
				
				/* final row */
				$calendar.= '</tr>';
				
				/* end the table */
				$calendar.= '</table>';
				
				// add table code to return
				$return = $return.$calendar;
				
/* #################### CALENDAR CODE END */
				
				$return = $return.'</ul></li>';
			}
			$return = $return.'</ul>';
		}
		return $return;
	}
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

$timeslot = new TimeSlot();
?>
