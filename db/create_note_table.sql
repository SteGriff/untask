-- drop table didit_note
create table didit_note(
	ID serial,
	primary key(ID),
	ParentID BIGINT UNSIGNED,
	UserID BIGINT UNSIGNED NOT NULL,
	Content nvarchar(255)
)
insert into didit_note(ParentID, UserID, Content)
values (null, 1, "Create Didit"), (1, 1, "Write database")