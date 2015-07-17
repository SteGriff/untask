select * from childTest 
join
(
select @rownum := @rownum + 1 as Rank
	from 
	(
		select *
		from childTest
		where PID is null
	) as x
JOIN (SELECT @rownum := 0) r
) as root
on childTest.ID = root.Rank or childTest.PID = root.Rank
order by root.Rank