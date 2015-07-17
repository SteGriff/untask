select *, COALESCE(PID,ID) as r from childTest 
order by r