<?php
/**
 * Programmer Block
 * Name : Eunice De Grace Fmukam Ngadjou
 * Class: CMPE 2550 Web Application Development
 * Date: January 20, 2025
 * Assignment: ICA 2 - Tic Tac Toe Game
 * Description: Tic Tac Toe game main interface file.
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="TicTacToe.js"></script>
    <link rel="stylesheet" href="TicTacToe.css">

</head>

<body>
    <header>CMPE2550 - Assignment 02 - Tic Tac Toe</header>

    <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="GET">
        <label id="names">Enter your names belows</label><br>
        <input type="text" name="player1" id="p1" placeholder="Player one name here!">
        <input type="text" name="player2" id="p2" placeholder="Player two name here!">
        <input type="button" name="newGame" id="nw" value="New Game">
        <input type="button" name="quitGame" id="qg" value="Quit Game">
        <div id="board" name="board">
            <input type="text" id="0_0" readonly>
            <input type="text" id="0_1" readonly>
            <input type="text" id="0_2" readonly>
            <input type="text" id="1_0" readonly>
            <input type="text" id="1_1" readonly>
            <input type="text" id="1_2" readonly>
            <input type="text" id="2_0" readonly>
            <input type="text" id="2_1" readonly>
            <input type="text" id="2_2" readonly>
        </div>

    </form>
    <br> <br>
    <?php

    include("footer.php");
    ?>
</body>

</html>