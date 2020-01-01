DELIMITER //
CREATE PROCEDURE get_drivers
(
    IN given_start_time TIME,IN max_allo_time INT,IN _date DATE
)


BEGIN
DECLARE given_end_time DATETIME DEFAULT now();
SET given_start_time=TIMESTAMP(_date,given_start_time);
SET given_end_time=DATE_ADD(given_start_time, INTERVAL max_allo_time MINUTE);

(select * from driver where not exists
(select 1 from 
((select driver_id from (select  
    (
    CASE 
        WHEN (TIMEDIFF(given_start_time,trip_end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),given_end_time)>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF(given_start_time,trip_end_time)>0 ) THEN TIMEDIFF(given_start_time,trip_end_time)
        
        WHEN (TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),given_end_time)>0 ) THEN TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),given_end_time)
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from truck_trip_truck_route) as yogyay where jtime < 0 order by jtime desc limit 2)
union

(select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF(given_start_time,trip_end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),given_end_time)>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF(given_start_time,trip_end_time)>0 ) THEN TIMEDIFF(given_start_time,trip_end_time)
        
        WHEN (TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),given_end_time)>0 ) THEN TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),given_end_time)
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from truck_trip_truck_route) as yogyag where jtime > 0 order by jtime asc limit 2)
 UNION
 (select driver_id from (select  TIMESTAMP(trip_date,trip_start_time),
    (
    CASE 
        WHEN (TIMEDIFF(trip_end_time,given_start_time)>=0 and TIMEDIFF(given_end_time,TIMESTAMP(trip_date,trip_start_time))>=0) THEN driver_id
        
        
        ELSE -1
    END) AS driver_id
    
    
 from truck_trip_truck_route) as yogyat ))
 as yogya
 where yogya.driver_id=driver.id)
 )
 INTERSECT
(select * from driver where not exists
(select 1 from
 (select sum(max_time_allocation),driver_id,trip_date from  truck_trip_truck_route where timestamp(trip_date) between DATE_SUB(given_start_time, INTERVAL 7 DAY) and given_start_time 
 group by driver_id,trip_date HAVING SUM(max_time_allocation) >40*60)as yogyatg
 where yogyatg.driver_id=driver.id));

END //
DELIMITER ;



===============================================================================================================================================
===============================================================================================================================================
=====================================================================================================
========================================================================

select * from driver where not exists
(select 1 from
(
select sum(max_time_allocation),driver_id,trip_date from  truck_trip_truck_route where timestamp(trip_date) between  DATE_SUB('2019-01-08 11:30:00'
, INTERVAL 7 DAY) and '2019-01-08 11:30:00'
 group by driver_id,trip_date HAVING SUM(max_time_allocation) >40*60)as yogyatg
 where yogyatg.driver_id=driver.id);

call get_drivers('23:10:20',60,'2019-11-03');
(select * from driver where not exists
(select 1 from 
((select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF('2019-12-28 20:20:20',trip_end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(trip_start_time,'2019-12-28 21:20:20')>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF('2019-12-28 20:20:20',trip_end_time)>0 ) THEN TIMEDIFF('2019-12-28 20:20:20',trip_end_time)
        
        WHEN (TIMEDIFF(trip_start_time,'2019-12-28 21:20:20')>0 ) THEN TIMEDIFF(trip_start_time,'2019-12-28 21:20:20')
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from truck_trip_truck_route) as yogyay where jtime < 0 order by jtime desc limit 2)
union

(select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF('2019-12-28 20:20:20',trip_end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(trip_start_time,'2019-12-28 21:20:20')>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF('2019-12-28 20:20:20',trip_end_time)>0 ) THEN TIMEDIFF('2019-12-28 20:20:20',trip_end_time)
        
        WHEN (TIMEDIFF(trip_start_time,'2019-12-28 21:20:20')>0 ) THEN TIMEDIFF(trip_start_time,'2019-12-28 21:20:20')
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from truck_trip_truck_route) as yogyag where jtime > 0 order by jtime asc limit 2)
 UNION
 (select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF(trip_end_time,'2019-12-28 20:20:20')>=0 and TIMEDIFF('2019-12-28 21:20:20',trip_start_time)>=0) THEN driver_id
        
        
        ELSE -1
    END) AS driver_id
    
    
 from truck_trip_truck_route) as yogyat )) as yogya
 where yogya.driver_id=driver.id)
 )