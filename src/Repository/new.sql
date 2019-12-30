DELIMITER //
CREATE PROCEDURE get_drivers_trips
(
    IN start_time TIME,IN max_allo_time INT,IN _date DATE,IN store_id INT
)


BEGIN
DECLARE given_end_time DATETIME DEFAULT now();
DECLARE given_start_time DATETIME DEFAULT now();
SET given_start_time=TIMESTAMP(_date,start_time);
SET given_end_time=DATE_ADD(given_start_time, INTERVAL max_allo_time MINUTE);

select * from driver where driver.store_id=store_id and not exists
(select 1 from

((select driver_id from truck_trip_route_time inner join
(select distinct trip_end_time from
 (select driver_id,trip_end_time,
  TIMESTAMPDIFF(SECOND,trip_end_time,given_start_time) as diff from truck_trip_route_time 
  where TIMESTAMPDIFF(SECOND,trip_end_time,given_start_time)>0
    
   )as one order by diff asc limit 2) as two on truck_trip_route_time.trip_end_time=two.trip_end_time)
 
union



(select driver_id from truck_trip_route_time inner join
(select distinct trip_start_time from
 (select driver_id,trip_start_time,
  TIMESTAMPDIFF(SECOND,given_end_time,trip_start_time) as diff from truck_trip_route_time 
  where TIMESTAMPDIFF(SECOND,given_end_time,trip_start_time)>0
    
   )as one order by diff asc limit 2) as two on truck_trip_route_time.trip_start_time=two.trip_start_time)
 

union


 (select driver_id
   from truck_trip_route_time 
  where (TIMEDIFF(trip_end_time,given_start_time)>=0 and TIMEDIFF(given_end_time,trip_start_time)>=0)
    
   )
 

union
 
(select driver_id from
 (select sum(max_time_allocation),driver_id,trip_date from  truck_trip_truck_route where timestamp(trip_date) between DATE_SUB(given_start_time, INTERVAL 7 DAY) and given_start_time 
 group by driver_id,trip_date HAVING SUM(max_time_allocation) >40*60) as one)
 )as zero 
 where zero.driver_id=driver.id )
 ;


END //
DELIMITER ;
=====================================================================================================
=====================================================================================================
DELIMITER //
CREATE PROCEDURE get_driver_assistants_trips
(
    IN start_time TIME,IN max_allo_time INT,IN _date DATE,IN store_id INT
)


BEGIN
DECLARE given_end_time DATETIME DEFAULT now();
DECLARE given_start_time DATETIME DEFAULT now();
SET given_start_time=TIMESTAMP(_date,start_time);
SET given_end_time=DATE_ADD(given_start_time, INTERVAL max_allo_time MINUTE);

select * from driver_assistant where driver_assistant.store_id=store_id and not exists
(select 1 from

((select driver_assistant_id from truck_trip_route_time inner join
(select distinct trip_end_time from
 (select driver_assistant_id,trip_end_time,
  TIMESTAMPDIFF(SECOND,trip_end_time,given_start_time) as diff from truck_trip_route_time 
  where TIMESTAMPDIFF(SECOND,trip_end_time,given_start_time)>0
    
   )as one order by diff asc limit 1) as two on truck_trip_route_time.trip_end_time=two.trip_end_time)
 
union



(select driver_assistant_id from truck_trip_route_time inner join
(select distinct trip_start_time from
 (select driver_assistant_id,trip_start_time,
  TIMESTAMPDIFF(SECOND,given_end_time,trip_start_time) as diff from truck_trip_route_time 
  where TIMESTAMPDIFF(SECOND,given_end_time,trip_start_time)>0
    
   )as one order by diff asc limit 1) as two on truck_trip_route_time.trip_start_time=two.trip_start_time)
 

union


 (select driver_assistant_id
   from truck_trip_route_time 
  where (TIMEDIFF(trip_end_time,given_start_time)>=0 and TIMEDIFF(given_end_time,trip_start_time)>=0)
    
   )
 

union
 
(select driver_assistant_id from
 (select sum(max_time_allocation),driver_assistant_id,trip_date from  truck_trip_truck_route where timestamp(trip_date) between DATE_SUB(given_start_time, INTERVAL 7 DAY) and given_start_time 
 group by driver_assistant_id,trip_date HAVING SUM(max_time_allocation) >60*60) as one)
 )as zero 
 where zero.driver_assistant_id=driver_assistant.id )
 ;


END //
DELIMITER ;


DELIMITER $$
CREATE EVENT `Every_2_Minutes_Cleanup`
  ON SCHEDULE EVERY 2 MINUTE STARTS '2019-12-31 00:00:00'
  ON COMPLETION PRESERVE
DO BEGIN
   update truck_trip set deleted_at=now() 
   where TIMESTAMPDIFF(MINUTE, created_at, now())>10 and (truck_id is null or driver_id is null or driver_assistant_id is null); 
   
END;$$
DELIMITER ;