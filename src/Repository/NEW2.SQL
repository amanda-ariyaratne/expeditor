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
(select driver_id from (select  
    (
    CASE 
        WHEN (TIMEDIFF(given_start_time,trip_end_time)>0 ) THEN driver_id
        
        WHEN (TIMEDIFF(trip_start_time,given_end_time)>0 ) THEN driver_id
        ELSE -1
    END) AS driver_id,
    (
    CASE 
        WHEN (TIMEDIFF(given_start_time,trip_end_time)>0 ) THEN TIMEDIFF(given_start_time,trip_end_time)
        
        WHEN (TIMEDIFF(trip_start_time,given_end_time)>0 ) THEN TIMEDIFF(trip_start_time,given_end_time)
        ELSE '9999-12-31 00:00:00'
    END) AS jtime
    
 from truck_trip_truck_route) as yogyay where jtime < 0 order by jtime desc limit 2)))
END //
DELIMITER ;

=========================
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
    
 from truck_trip_truck_route) as yogyag where jtime > 0 order by jtime asc limit 2)
 UNION

(
select driver_id from (select  truck_trip_id,trip_end_time,TIMESTAMP(trip_date,trip_start_time),
    (
    CASE 
        WHEN (TIMEDIFF(trip_end_time,'2018-11-03 23:10:00')>=0 and TIMEDIFF('2018-11-04 00:20:00',TIMESTAMP(trip_date,trip_start_time))>=0) THEN driver_id
        
        
        ELSE -1
    END) AS driver_id 
    from truck_trip_truck_route) as yogyat)
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
    
 from truck_trip_truck_route) as yogyay where jtime < 0 order by jtime desc limit 2)
)
    
    
    as yogya
 where yogya.driver_id=driver.id)
 INTERSECT
(select * from driver where not exists
(select 1 from
 (select sum(max_time_allocation),driver_id,trip_date from  truck_trip_truck_route where timestamp(trip_date) between DATE_SUB('2018-11-03 23:10:00', INTERVAL 7 DAY) and '2018-11-03 23:10:00' 
 group by driver_id,trip_date HAVING SUM(max_time_allocation) >40*60)as yogyatg
 where yogyatg.driver_id=driver.id));
 


===============================
    select driver_id from (select  truck_trip_id,TIMESTAMP(trip_date,trip_end_time),driver_id,TIMESTAMP(trip_date,trip_start_time),
    (
    CASE 
        WHEN ((SECOND('2018-11-04 00:10:00')-SECOND('2018-11-03 23:10:00'))>=0 and (SECOND('2018-11-03 24:20:00')-SECOND(' 2018-11-03 23:10:00'))>=0) THEN driver_id
        
        
        ELSE -1
    END) AS driver_id2 
    from truck_trip_truck_route) as yogyat


    create view truck_trip_route_time as
    SELECT
    `expeditor`.`truck_trip`.`id` AS `truck_trip_id`,
    TIMESTAMP(`expeditor`.`truck_trip`.`date`,`expeditor`.`truck_trip`.`start_time`) AS `trip_start_time`,
    
    TIMESTAMP(`expeditor`.`truck_trip`.`date`,`expeditor`.`truck_trip`.`start_time` )+ INTERVAL `expeditor`.`truck_route`.`max_time_allocation` MINUTE AS `trip_end_time`,
    `expeditor`.`truck_trip`.`driver_id` AS `driver_id`,
    `expeditor`.`truck_route`.`store_id` AS `store_id`,
    `expeditor`.`truck_trip`.`driver_assistant_id` AS `driver_assistant_id`,
    `expeditor`.`truck_trip`.`truck_id` AS `truck_id`,
    `expeditor`.`truck_trip`.`truck_route_id` AS `truck_route_id`,
    `expeditor`.`truck_route`.`max_time_allocation` AS `max_time_allocation`
FROM
    (
        `expeditor`.`truck_trip`
    LEFT JOIN `expeditor`.`truck_route` ON
        (
            `expeditor`.`truck_trip`.`truck_route_id` = `expeditor`.`truck_route`.`id`
        )
    )
ORDER BY
    `expeditor`.`truck_trip`.`id`;

id,delivery_date,truck_route_id,status_id,created_at
    create view purchase_truck_trip as 
    SELECT
    `purchase_volume`.`purchase_id` AS `purchase_id`,
    `purchase_volume`.`product_id` AS `product_id`,
    `purchase_volume`.`volume` AS `volume`,
    `expeditor`.`purchase`.`delivery_date` AS `delivery_date`,
    `expeditor`.`purchase`.`truck_route_id` AS `truck_route_id`,
    `expeditor`.`purchase`.`status_id` AS `status_id`,
    `expeditor`.`purchase`.`store_id` AS `store_id`,
    `expeditor`.`store`.`name` AS `store_name`,
    `expeditor`.`purchase`.`truck_trip_id` AS `truck_trip_id`,
    `expeditor`.`purchase`.`created_at` AS `created_at`
FROM
    (
        (
            `expeditor`.`purchase`
        LEFT JOIN `expeditor`.`store` ON
            (
                `expeditor`.`purchase`.`store_id` = `expeditor`.`store`.`id`
            )
        )
    LEFT JOIN `expeditor`.`purchase_volume` ON
        (
            `expeditor`.`purchase`.`id` = `purchase_volume`.`purchase_id`
        )
    )
    




select * from driver where not exists
(select 1 from 

(select driver_id from (select  TIMESTAMP(trip_date,trip_start_time),
    (
    CASE 
        WHEN (TIMEDIFF(trip_end_time,given_start_time)>=0 and TIMEDIFF(given_end_time,TIMESTAMP(trip_date,trip_start_time))>=0) THEN driver_id
        
        
        ELSE -1
    END) AS driver_id
    
    
 from truck_trip_truck_route) as yogyat )




 select sum(max_time_allocation),driver_id,trip_date from  truck_trip_truck_route where timestamp(trip_date) between  DATE_SUB('2019-01-08 11:30:00'
, INTERVAL 7 DAY) and '2019-01-08 11:30:00'
 group by driver_id,trip_date HAVING SUM(max_time_allocation) <40*60



select driver_id from truck_trip_truck_route where 
 timestamp(trip_date) between  DATE_SUB('2019-01-08 11:30:00'
, INTERVAL 7 DAY) and '2019-01-08 11:30:00';