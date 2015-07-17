-- drop table didit_user
create table didit_user(
	ID serial,
	primary key(ID),
	Username varchar(20)
)

alter table didit_user
add column Password varchar(50) not null

insert into didit_user(Username, Password)
values ('ste', '$1$jxMclx30$FDAIbTxatKDfdR7HPR.CQ1')