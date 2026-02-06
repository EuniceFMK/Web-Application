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
    <header>CMPE2550 - LAB1 - OTHELLO</header>

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
            <input type="text" id="0_3" readonly>
            <input type="text" id="0_4" readonly>
            <input type="text" id="0_5" readonly>
            <input type="text" id="0_6" readonly>
            <input type="text" id="0_7" readonly>
            <input type="text" id="1_0" readonly>
            <input type="text" id="1_1" readonly>
            <input type="text" id="1_2" readonly>
            <input type="text" id="1_3" readonly>
            <input type="text" id="1_4" readonly>
            <input type="text" id="1_5" readonly>
            <input type="text" id="1_6" readonly>
            <input type="text" id="1_7" readonly>
            <input type="text" id="2_0" readonly>
            <input type="text" id="2_1" readonly>
            <input type="text" id="2_2" readonly>
            <input type="text" id="2_3" readonly>
            <input type="text" id="2_4" readonly>
            <input type="text" id="2_5" readonly>
            <input type="text" id="2_6" readonly>
            <input type="text" id="2_7" readonly>
            <input type="text" id="3_0" readonly>
            <input type="text" id="3_1" readonly>
            <input type="text" id="3_2" readonly>
            <input type="text" id="3_3" readonly>
            <input type="text" id="3_4" readonly>
            <input type="text" id="3_5" readonly>
            <input type="text" id="3_6" readonly>
            <input type="text" id="3_7" readonly>
            <input type="text" id="4_0" readonly>
            <input type="text" id="4_1" readonly>
            <input type="text" id="4_2" readonly>   
            <input type="text" id="4_3" readonly>
            <input type="text" id="4_4" readonly>
            <input type="text" id="4_5" readonly>
            <input type="text" id="4_6" readonly>
            <input type="text" id="4_7" readonly>
            <input type="text" id="5_0" readonly>
            <input type="text" id="5_1" readonly>
            <input type="text" id="5_2" readonly>
            <input type="text" id="5_3" readonly>           
            <input type="text" id="5_4" readonly>
            <input type="text" id="5_5" readonly>
            <input type="text" id="5_6" readonly>
            <input type="text" id="5_7" readonly>
            <input type="text" id="6_0" readonly>
            <input type="text" id="6_1" readonly>
            <input type="text" id="6_2" readonly>
            <input type="text" id="6_3" readonly>
            <input type="text" id="6_4" readonly>
            <input type="text" id="6_5" readonly>
            <input type="text" id="6_6" readonly>
            <input type="text" id="6_7" readonly>
            <input type="text" id="7_0" readonly>
            <input type="text" id="7_1" readonly>
            <input type="text" id="7_2" readonly>
            <input type="text" id="7_3" readonly>
            <input type="text" id="7_4" readonly>
            <input type="text" id="7_5" readonly>
            <input type="text" id="7_6" readonly>
            <input type="text" id="7_7" readonly>
        </div>

    </form>
    <br> <br>
    <?php

    include("footer.php");
    ?>
</body>

</html>