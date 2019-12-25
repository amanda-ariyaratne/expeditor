(select * from driver 
where not exists
(select  driver_id from trucktrip_driver_driverassist_truck where driver.id=trucktrip_driver_driverassist_truck.driver_id order by 
(('' )-end_time() )
DESC limit 2 ))
INTERSECT
(select * from driver where not exists
 (select sum(max_time_allocation),driver_id,date from  trucktrip_driver_driverassist_truck where date between '2010:02:02' and '2020-01-01' 
 and driver.id=trucktrip_driver_driverassist_truck.driver_id group by driver_id,date HAVING SUM(max_time_allocation) <40*60));

select * from driver where not exists
(select  trip_start_time,
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
    
 from trucktrip_driver_driverassist_truck_route
 where driver.id=trucktrip_driver_driverassist_truck_route.driver_id 
 order by jtime);

(select driver_id from (select  trip_start_time,
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
    
 from trucktrip_driver_driverassist_truck_route) as yogya where jtime < 0 order by jtime desc limit 1)
union

(select driver_id from (select  trip_start_time,
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
    
 from trucktrip_driver_driverassist_truck_route) as yogyag where jtime > 0 order by jtime asc limit 1)
 UNION
 (select driver_id from (select  trip_start_time,
    (
    CASE 
        WHEN (TIMEDIFF(now(),end_time)<0 and TIMEDIFF(trip_start_time,now())<0) THEN driver_id
        
        WHEN (TIMEDIFF(now(),end_time)>0 and TIMEDIFF(trip_start_time,now())<0) THEN driver_id
        ELSE -1
    END) AS driver_id
    
    
 from trucktrip_driver_driverassist_truck_route) as yogyat )

 DELIMITER $$
 
CREATE FUNCTION calcWork(
    @date date
) 
RETURNS boolean
DETERMINISTIC
BEGIN
    DECLARE work boolean;
 
    IF credit > 50000 THEN
        SET customerLevel = 'PLATINUM';
    ELSEIF (credit >= 50000 AND 
            credit <= 10000) THEN
        SET customerLevel = 'GOLD';
    ELSEIF credit < 10000 THEN
        SET customerLevel = 'SILVER';
    END IF;
    -- return the customer level
    RETURN (customerLevel);
END$$
DELIMITER ;

(
    CASE 
        WHEN (TIMEDIFF(now(),end_time)>0 ) THEN TIMEDIFF(now(),end_time)
        
        WHEN (TIMEDIFF(trip_start_time,now())>0 ) THEN TIMEDIFF(trip_start_time,now())
        ELSE -1
    END) AS jtime