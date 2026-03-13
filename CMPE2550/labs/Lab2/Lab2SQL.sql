-- drop table if exists UserRoles
-- drop table if exists Users
-- drop table if exists Roles
-- go

-- create table Roles 
-- (
--    roleId int identity(1,1) not null
--           constraint PK_RoleId primary key,       
--    roleName varchar(40) not null,
--    roleDescription varchar(40),
--    roleValue int not null
-- )
-- go

-- create table Users
-- (
--   userId int identity (1,1) not null
--          constraint PK_Id primary key,
--   userName varchar (40) not null,
--   uPassword varchar (40) not null
-- )
-- go

-- create table UsersRoles
-- (
--   userId int not null 
--          constraint FK_UsersRoles_Users
--          references Users(userId),
--   roleId int not null
--          constraint FK_UsersRoles_Roles
--          references Roles(roleId),
--   constraint PK_UsersRoles primary key (userId, roleId)
-- )
-- go


-- insert into Roles (roleName, roleDescription, roleValue)
-- values 
-- ('Root', 'Full system control', 100),
-- ('Admin', 'Administrative access', 50),
-- ('Default', 'Basic user role', 1)

-- insert into Users (userName, uPassword)
-- values ('root', 'rootpassword')

-- insert into UsersRoles (userId, roleId)
-- values (1, 1)

-- create unique index UX_OnlyOneRoot
-- on UsersRoles(roleId)
-- where roleId = 1   

DROP TABLE IF EXISTS UsersRoles;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Roles;

CREATE TABLE Roles 
(
   roleId INT AUTO_INCREMENT PRIMARY KEY,
   roleName VARCHAR(40) NOT NULL,
   roleDescription VARCHAR(40),
   roleValue INT NOT NULL
);

CREATE TABLE Users
(
  userId INT AUTO_INCREMENT PRIMARY KEY,
  userName VARCHAR(40) NOT NULL,
  uPassword VARCHAR(255) NOT NULL
);

CREATE TABLE UsersRoles
(
  userId INT NOT NULL,
  roleId INT NOT NULL,
  PRIMARY KEY (userId, roleId),
  CONSTRAINT FK_UsersRoles_Users
    FOREIGN KEY (userId) REFERENCES Users(userId),
  CONSTRAINT FK_UsersRoles_Roles
    FOREIGN KEY (roleId) REFERENCES Roles(roleId)
);

INSERT INTO Roles (roleName, roleDescription, roleValue)
VALUES 
('Root', 'Full system control', 100),
('Admin', 'Administrative access', 50),
('Member', 'Basic user role', 1);

INSERT INTO Users (userName, uPassword)
VALUES ('root', '$2y$10$8LKA5sLgYYmdLafQRnKkTOqOTEIRcWzEDGehK3Q7sXmMNWWJdLfTi');

INSERT INTO UsersRoles (userId, roleId)
VALUES (1, 1);