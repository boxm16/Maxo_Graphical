select trip_voucher_number, type, start_time_scheduled, arrival_time_scheduled,  start_time_actual, arrival_time_actual,  
timediff(arrival_time_scheduled, start_time_scheduled) as trip_period_time_scheduled, 
timediff(arrival_time_actual, start_time_actual) as trip_period_time_actual, 
timediff(timediff(arrival_time_scheduled, start_time_scheduled), timediff(arrival_time_actual, start_time_actual) ) as difference, 
if(timediff(timediff(arrival_time_scheduled, start_time_scheduled), timediff(arrival_time_actual, start_time_actual) )>=time_to_sec((timediff(arrival_time_scheduled, start_time_scheduled)/100)*(-40)) AND timediff(timediff(arrival_time_scheduled, start_time_scheduled), timediff(arrival_time_actual, start_time_actual))<0, true, false) as lowPercentage,
if(timediff(timediff(arrival_time_scheduled, start_time_scheduled), timediff(arrival_time_actual, start_time_actual) )<=time_to_sec((timediff(arrival_time_scheduled, start_time_scheduled)/100)*40) AND timediff(timediff(arrival_time_scheduled, start_time_scheduled), timediff(arrival_time_actual, start_time_actual) )>=0, true, false) as highPercentage
 from trip_period;