USE master
GO

drop database if exists efmukam_CheckersDB
CREATE DATABASE [efmukam_CheckersDB]
GO

USE [efmukam_CheckersDB]
GO

/* PART 1 : DROP TABLES */

DROP TABLE IF EXISTS GameMoves
DROP TABLE IF EXISTS Games
GO


/* PART 2 : CREATE TABLES */

CREATE TABLE Games
(
    GameId INT
        IDENTITY(1,1)
        CONSTRAINT PK_GameId
            PRIMARY KEY NOT NULL,

    Player1 VARCHAR(50) NOT NULL,

    Player2 VARCHAR(50) NOT NULL,

    CurrentTurn VARCHAR(50) NOT NULL,

    Winner VARCHAR(50) NULL,

    GameStatus VARCHAR(20)
        CONSTRAINT DF_GameStatus
            DEFAULT ('Active')
        NOT NULL,

    StartDate DATETIME
        CONSTRAINT DF_StartDate
            DEFAULT GETDATE()
        NOT NULL
)
GO


CREATE TABLE GameMoves
(
    MoveId INT
        IDENTITY(1,1)
        CONSTRAINT PK_MoveId
            PRIMARY KEY NOT NULL,

    GameId INT
        CONSTRAINT FK_GameMoves_Games
            FOREIGN KEY REFERENCES Games(GameId)
        NOT NULL,

    PlayerName VARCHAR(50) NOT NULL,

    FromPosition VARCHAR(10) NOT NULL,

    ToPosition VARCHAR(10) NOT NULL,

    MoveDate DATETIME
        CONSTRAINT DF_MoveDate
            DEFAULT GETDATE()
        NOT NULL
)
GO


/* PART 3 : SAMPLE DATA */

INSERT INTO Games
(
    Player1,
    Player2,
    CurrentTurn,
    Winner,
    GameStatus
)
VALUES
(
    'Alice',
    'Bob',
    'Alice',
    NULL,
    'Active'
),
(
    'John',
    'Mike',
    'Mike',
    'Mike',
    'Finished'
)
GO


INSERT INTO GameMoves
(
    GameId,
    PlayerName,
    FromPosition,
    ToPosition
)
VALUES
(1, 'Alice', 'A3', 'B4'),
(1, 'Bob', 'H6', 'G5'),
(1, 'Alice', 'B4', 'C5'),
(2, 'John', 'A1', 'B2'),
(2, 'Mike', 'H8', 'G7')
GO