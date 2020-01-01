DELIMITER //
CREATE PROCEDURE get_drivers
(
    IN start_time TIME,IN max_allo_time INT,IN _date DATE
)


BEGIN
DECLARE given_end_time DATETIME DEFAULT now();
DECLARE given_start_time DATETIME DEFAULT now();
SET given_start_time=TIMESTAMP(_date,start_time);
SET given_end_time=DATE_ADD(given_start_time, INTERVAL max_allo_time MINUTE);


select driver_id from truck_trip_route_time inner join
(select distinct trip_end_time from
 (select driver_id,trip_end_time,
  TIMESTAMPDIFF(SECOND,trip_end_time,given_start_time) as diff from truck_trip_route_time 
  where TIMESTAMPDIFF(SECOND,trip_end_time,given_start_time)>0
    
   )as one order by diff asc limit 2) as two on truck_trip_route_time.trip_end_time=two.trip_end_time
 ;


END //
DELIMITER ;
=======================================================================================
=================================================================

DELIMITER //
CREATE PROCEDURE get_drivers
(
    IN start_time TIME,IN max_allo_time INT,IN _date DATE
)


BEGIN
DECLARE given_end_time DATETIME DEFAULT now();
DECLARE given_start_time DATETIME DEFAULT now();
SET given_start_time=TIMESTAMP(_date,start_time);
SET given_end_time=DATE_ADD(given_start_time, INTERVAL max_allo_time MINUTE);


select driver_id from truck_trip_route_time inner join
(select distinct trip_start_time from
 (select driver_id,trip_start_time,
  TIMESTAMPDIFF(SECOND,given_end_time,trip_start_time) as diff from truck_trip_route_time 
  where TIMESTAMPDIFF(SECOND,given_end_time,trip_start_time)>0
    
   )as one order by diff asc limit 2) as two on truck_trip_route_time.trip_start_time=two.trip_start_time
 ;


END //
DELIMITER ;
===============================================================================
===============================================================================
DELIMITER //
CREATE PROCEDURE get_drivers
(
    IN start_time TIME,IN max_allo_time INT,IN _date DATE
)


BEGIN
DECLARE given_end_time DATETIME DEFAULT now();
DECLARE given_start_time DATETIME DEFAULT now();
SET given_start_time=TIMESTAMP(_date,start_time);
SET given_end_time=DATE_ADD(given_start_time, INTERVAL max_allo_time MINUTE);




 (select driver_id,trip_end_time
   from truck_trip_route_time 
  where (TIMEDIFF(trip_end_time,given_start_time)>=0 and TIMEDIFF(given_end_time,trip_start_time)>=0)
    
   )
 ;


END //
DELIMITER ;
======================================
DELIMITER //
CREATE PROCEDURE get_drivers
(
    IN start_time TIME,IN max_allo_time INT,IN _date DATE
)


BEGIN
DECLARE given_end_time DATETIME DEFAULT now();
DECLARE given_start_time DATETIME DEFAULT now();
SET given_start_time=TIMESTAMP(_date,start_time);
SET given_end_time=DATE_ADD(given_start_time, INTERVAL max_allo_time MINUTE);




 

 (select sum(max_time_allocation),driver_id,trip_date from  truck_trip_truck_route where timestamp(trip_date) between DATE_SUB(given_start_time, INTERVAL 7 DAY) and given_start_time 
 group by driver_id,trip_date HAVING SUM(max_time_allocation) >40*60);


END //
DELIMITER ;