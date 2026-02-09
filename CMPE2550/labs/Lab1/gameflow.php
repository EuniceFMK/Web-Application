<?php
/**
 * Programmer Block
 * Name : Eunice De Grace Fmukam Ngadjou
 * Class: CMPE 2550 Web Application Development    
 * Date: January 20, 2025
 * Assignment: Lab 1 - Othello Game
 * Description: Gameflow.php file to handle the game logic and AJAX requests for the Othello game.
 */
session_start(); // Start the session to store game state
error_log(json_encode($_GET));   // Log incoming GET request for debugging

$clean = array();  // Initialize an array to hold sanitized inputs
// Sanitize all incoming GET parameters
foreach ($_GET as $key => $value)
    $clean[trim(strip_tags(htmlspecialchars($key)))] = strip_tags(htmlspecialchars($value));
//error_log($clean);
$output = array();   // Initialize an array to hold output data
$p1 = trim($clean["player1"] ?? "");   // Get and sanitize player 1 name
$p2 = trim($clean["player2"] ?? "");   // Get and sanitize player 2 name

$action = $clean["action"] ?? "";  // Get the action to be performed
$output["gameOver"] = false;      // Initialize gameOver status

$DIRECTIONS = [
    [-1, -1],
    [-1, 0],
    [-1, 1],
    [0, -1],
    [0, 1],
    [1, -1],
    [1, 0],
    [1, 1]
];
//error_log($clean["action"]);
// Handle different game actions based on the 'action' parameter
switch ($action) {

    // Initialize the game board and reset current player
    case "InitGame":
        InitGame();
        break;
    // Start a new game with player names and randomly select the first player
    case "NewGame":
        newGame();
        break;

    // Process a player's move
    case "PlayMove":
        playMove();
        break;

    // Quit the game and clear session data
    case "QuitGame":

        session_destroy();  // Destroy the session to clear game state
        $_SESSION = [];    // Clear session array
        $output["status"] = "Game reset";  // Set status message
        break;
}

echo json_encode($output);  // Output the response as JSON
die();

/**
 * FunctionName:    nullGame
 * Inputs:          $board - 2D array representing the tic-tac-toe board
 * Outputs:         Boolean - true if the board is full (draw), false otherwise
 * Decription:      Checks if the board is full (draw).
 */

function playMove()
{
    //error_log("In playMove()");
    global $output, $clean;

    $row = intval($clean["row"] ?? -1);         // Get and sanitize row input
    $col = intval($clean["col"] ?? -1);        // Get and sanitize column input

    // Validate row and column inputs
    if ($row < 0 || $row > 7 || $col < 0 || $col > 7) {
        $output["status"] = "Invalid move";        // Set error message
        $output["board"] = $_SESSION["board"];    // Return the current board
        return;
    }

    // Check if the game is already over
    if (!empty($_SESSION["gameOver"])) {
        $output["status"] = "Game is already over. Start a new game.";   // Set error message
        $output["board"] = $_SESSION["board"];    // Return the current board
        $output["gameOver"] = true;      // Indicate game over status
        return;
    }

    // Ensure both players and current player are set
    if (!isset($_SESSION["player1"]) || !isset($_SESSION["player2"]) || !isset($_SESSION["currentPlayer"]) || !isset($_SESSION["mark"])) {
        $output["status"] = "Start a new Game first.";   // Set error message
        $output["board"] = $_SESSION["board"] ?? [];     // Return the current board if it exists
        return;
    }

    // Ensure the game board is initialized
    if (!isset($_SESSION["board"])) {
        $output["status"] = "Game not initialized";   // Set error message
        return;
    }

    // Check if the selected cell is already taken
    if ($_SESSION["board"][$row][$col] != "") {
        $output["status"] = "Cell already taken";     // Set error message
        $output["board"] = $_SESSION["board"];       // Return the current board
        return;
    }
    $mark = $_SESSION["mark"];                          // Get the current player's mark ('X' or 'O')


    $flips = getFlips($_SESSION["board"], $row, $col, $mark);  // Get the list of pieces to flip
    if (count($flips) == 0) {
        $output["status"] = "Invalid move ";  // Set error message
        $output["board"] = $_SESSION["board"];    // Return the current board
         $output["status"] .= $_SESSION["currentPlayer"] . "'s turn";  // Update status message
        return;
    }
    $_SESSION["board"][$row][$col] = $mark;            // Place the mark on the board
    foreach ($flips as $flip) {
        $_SESSION["board"][$flip[0]][$flip[1]] = $mark;
    }

    switchPlayer();
    if (!hasValidMove($_SESSION["board"], $_SESSION["mark"])) {
        switchPlayer();
        if (!hasValidMove($_SESSION["board"], $_SESSION["mark"])) {
            $_SESSION["gameOver"] = true;  // Set game over status
            $output["gameOver"] = true;    // Indicate game over in output
            $output["status"] = "Game Over! No valid moves left.";  // Set game over message
            $output["board"] = $_SESSION["board"];

        } else {
            $output["status"] = $_SESSION["currentPlayer"] . " plays again (opponent has no valid move)";  // Update status message
            $output["board"] = $_SESSION["board"];

        }
        return;

    }
    $output["board"] = $_SESSION["board"];   // Return the updated board
    $output["status"] = $_SESSION["currentPlayer"] . "'s turn";  // Update status message

}
function InitGame()
{
    global $output;  // Access the global output array
    $_SESSION["board"] = [
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""]
    ];  // 8x8 empty board
    $_SESSION["currentPlayer"] = ""; // Reset current player
    unset($_SESSION["mark"]);  // Clear the mark from session
    $output["status"] = "Game Initialized"; // Set status message
}

function newGame()
{
    global $output, $p1, $p2;  // Access global variables for output and player names
    $_SESSION["board"] = [
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""],
        ["", "", "", "", "", "", "", ""]
    ];  // 8x8 empty board


    unset($_SESSION["gameOver"]);  // Clear game over status
    $output["gameOver"] = false;  // Reset game over status in output

    // Validate player names
    if ($p1 == "" && $p2 == "") {
        unset($_SESSION["player1"]);  // Clear player names from session
        unset($_SESSION["player2"]);  // Clear player names from session
        unset($_SESSION["currentPlayer"]);  // Clear current player from session
        unset($_SESSION["mark"]);          // Clear mark from session
        $output["success"] = false;       // Indicate failure
        $output["status"] = "Names must be at least 1 character!";  // Set error message
    } else if ($p1 == "") {

        $output["success"] = false; // Indicate failure
        $output["status"] = "Player1 name is missing";  // Set error message

    } else if ($p2 == "") {
        $output["success"] = false;  // Indicate failure
        $output["status"] = "Player2 name is missing";  // Set error message

    } else {
        $output["status"] = "Game started successfully";   // Set success message
        // Randomly select the first player
        // positions initiales Othello
        $_SESSION["board"][3][3] = "O";
        $_SESSION["board"][3][4] = "X";
        $_SESSION["board"][4][3] = "X";
        $_SESSION["board"][4][4] = "O";

        $_SESSION["player1"] = $p1;   // Store player 1 name in session
        $_SESSION["player2"] = $p2;  // Store player 2 name in session
        $_SESSION["currentPlayer"] = $p1;   // Store current player in session
        if (rand(0, 1) == 0) {
            $_SESSION["currentPlayer"] = $p1;
            $_SESSION["mark"] = "X";
        } else {
            $_SESSION["currentPlayer"] = $p2;
            $_SESSION["mark"] = "O";
        }
        $output["status"] = $_SESSION["currentPlayer"] . " goes first (". $_SESSION["mark"] .")";   // Update status message
        $output["success"] = true;    // Indicate success
        $output["board"] = $_SESSION["board"];    // Return the initialized board
    }
}

function getFlips($board, $row, $col, $player)
{
    global $DIRECTIONS;
    if ($board[$row][$col] != "")
        return [];
    $opponent = ($player == "X") ? "O" : "X";
    $flip = [];

    foreach ($DIRECTIONS as [$dr, $dc]) {
        $r = $row + $dr;
        $c = $col + $dc;
        $temp = [];

        while ($r >= 0 && $r < 8 && $c >= 0 && $c < 8 && $board[$r][$c] == $opponent) {
            $temp[] = [$r, $c];
            $r += $dr;
            $c += $dc;
        }
        if (
            $r >= 0 && $r < 8 && $c >= 0 && $c < 8 &&
            $board[$r][$c] === $player && count($temp) > 0
        ) {
            $flip = array_merge($flip, $temp);
        }
    }
    return $flip;
}
function hasValidMove($board, $player)
{
    for ($r = 0; $r < 8; $r++) {
        for ($c = 0; $c < 8; $c++) {
            if (count(getFlips($board, $r, $c, $player)) > 0) {
                return true;
            }
        }
    }
    return false;
}

function switchPlayer()
{
    $_SESSION["mark"] = ($_SESSION["mark"] == "X") ? "O" : "X";
    $_SESSION["currentPlayer"] =
        ($_SESSION["currentPlayer"] == $_SESSION["player1"])
        ? $_SESSION["player2"]
        : $_SESSION["player1"];
}