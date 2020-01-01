DELIMITER //
CREATE PROCEDURE get_drivers
(
    IN given_start_time TIME,IN max_allo_time INT,IN _date DATE
)


BEGIN
DECLARE given_end_time DATETIME DEFAULT now();
SET given_start_time=TIMESTAMP(_date,given_start_time);
SET given_end_time=DATE_ADD(given_start_time, INTERVAL max_allo_time MINUTE);

select * from driver where not exists
(select 1 from (
(select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF('2018-11-03 23:10:00',trip_end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),'2018-11-04 00:20:00')>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF('2018-11-03 23:10:00',trip_end_time)>0 ) THEN TIMEDIFF('2018-11-03 23:10:00',trip_end_time)
        
        WHEN (TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),'2018-11-04 00:20:00')>0 ) THEN TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),'2018-11-04 00:20:00')
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from truck_trip_route_time) as yogyag where jtime > 0 order by jtime asc limit 2)
 UNION

(
select driver_id from (select  truck_trip_id,trip_end_time,TIMESTAMP(trip_date,trip_start_time),
    (
    CASE 
        WHEN (TIMEDIFF(trip_end_time,'2018-11-03 23:10:00')>=0 and TIMEDIFF('2018-11-04 00:20:00',TIMESTAMP(trip_date,trip_start_time))>=0) THEN driver_id
        
        
        ELSE -1
    END) AS driver_id 
    from truck_trip_route_time) as yogyat)
    union
    (select driver_id from (select  
    (
    CASE 
        WHEN (TIMEDIFF('2018-11-03 23:10:00',trip_end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),'2018-11-04 00:20:00')>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF('2018-11-03 23:10:00',trip_end_time)>0 ) THEN TIMEDIFF('2018-11-03 23:10:00',trip_end_time)
        
        WHEN (TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),'2018-11-04 00:20:00')>0 ) THEN TIMEDIFF(TIMESTAMP(trip_date,trip_start_time),'2018-11-04 00:20:00')
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from truck_trip_route_time) as yogyay where jtime < 0 order by jtime desc limit 2)
)
    
    
    as yogya
 where yogya.driver_id=driver.id)
 INTERSECT
(select * from driver where not exists
(select 1 from
 (select sum(max_time_allocation),driver_id,trip_date from  truck_trip_route_time where timestamp(trip_date) between DATE_SUB('2018-11-03 23:10:00', INTERVAL 7 DAY) and '2018-11-03 23:10:00' 
 group by driver_id,trip_date HAVING SUM(max_time_allocation) >40*60)as yogyatg
 where yogyatg.driver_id=driver.id));

 END //
DELIMITER ;
====================================
=================================
====================================
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
===========
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
===========================================================================================
=======================================================================================
(
select driver_id from (select  truck_trip_id,trip_end_time,start_time,
    (
    CASE 
        WHEN (TIMEDIFF(trip_end_time,given_start_time)>=0 and TIMEDIFF(given_end_time,start_time)>=0) THEN driver_id
        
        
        ELSE -1
    END) AS driver_id 
    from truck_trip_truck_route) )
 