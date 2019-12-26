
DELIMITER $$
CREATE PROCEDURE
get_drivers(IN trip_start_time DATETIME,IN end_time DATETIME)
BEGIN


(select * from driver where not exists
((select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF(now(),end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(trip_start_time,now())>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF(now(),end_time)>0 ) THEN TIMEDIFF(now(),end_time)
        
        WHEN (TIMEDIFF(trip_start_time,now())>0 ) THEN TIMEDIFF(now(),trip_start_time)
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from trucktrip_driver_driverassist_truck_route) as yogya where jtime < 0 order by jtime desc limit 2)
union

(select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF(given_start_time,end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(start_time,given_end_time)>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF(now(),end_time)>0 ) THEN TIMEDIFF(now(),end_time)
        
        WHEN (TIMEDIFF(trip_start_time,now())>0 ) THEN TIMEDIFF(now(),trip_start_time)
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from trucktrip_driver_driverassist_truck_route) as yogyag where jtime > 0 order by jtime asc limit 2)
 UNION
 (select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF(end_time,given_start_time)>=0 and TIMEDIFF(given_end_time,start_time)>=0) THEN driver_id
        
        
        ELSE -1
    END) AS driver_id
    
    
 from trucktrip_driver_driverassist_truck_route) as yogyat )))
 INTERSECT
(select * from driver where not exists
 (select sum(max_time_allocation),driver_id,date from  trucktrip_driver_driverassist_truck where date between '2010:02:02' and '2020-01-01' 
 and driver.id=trucktrip_driver_driverassist_truck.driver_id group by driver_id,date HAVING SUM(max_time_allocation) <40*60));
END$$
DELIMITER ;


 
