drop table if exists UserRoles
drop table if exists Users
drop table if exists Roles
go

create table Roles 
(
   roleId int identity(1,1) not null
          constraint PK_RoleId primary key,       
   roleName varchar(40) not null,
   roleDescription varchar(40),
   roleValue int not null
)
go

create table Users
(
  userId int identity (1,1) not null
         constraint PK_Id primary key,
  userName varchar (40) not null,
  uPassword varchar (40) not null
)
go

create table UsersRoles
(
  userId int not null 
         constraint FK_UsersRoles_Users
         references Users(userId),
  roleId int not null
         constraint FK_UsersRoles_Roles
         references Roles(roleId),
  constraint PK_UsersRoles primary key (userId, roleId)
)
go


insert into Roles (roleName, roleDescription, roleValue)
values 
('Root', 'Full system control', 100),
('Admin', 'Administrative access', 50),
('Default', 'Basic user role', 1)

insert into Users (userName, uPassword)
values ('root', 'rootpassword')

insert into UsersRoles (userId, roleId)
values (1, 1)

create unique index UX_OnlyOneRoot
on UsersRoles(roleId)
where roleId = 1   C'est bon?
