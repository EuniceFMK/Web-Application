<?php
/**
 * Programmer Block
 * Name : Eunice De Grace Fmukam Ngadjou
 * Class: CMPE 2550 Web Application Development    
 * Date: January 20, 2025
 * Assignment: ICA 2 - Tic Tac Toe Game
 * Description: Gameflow.php file to handle the game logic and AJAX requests for the Tic Tac Toe game.
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


//error_log($clean["action"]);
// Handle different game actions based on the 'action' parameter
switch ($action) {

    // Initialize the game board and reset current player
    case "InitGame":
        $_SESSION["board"] = [["", "", ""], ["", "", ""], ["", "", ""]];  // 3x3 empty board
        $_SESSION["currentPlayer"] = ""; // Reset current player
        $output["status"] = "Game Initialized"; // Set status message
        break;

    // Start a new game with player names and randomly select the first player
    case "NewGame":
        $_SESSION["board"] = [["", "", ""], ["", "", ""], ["", "", ""]];  // 3x3 empty board
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
            $first = (rand(1, 2) == 1) ? $p1 : $p2;
            $_SESSION["player1"] = $p1;   // Store player 1 name in session
            $_SESSION["player2"] = $p2;  // Store player 2 name in session
            $_SESSION["currentPlayer"] = $first;   // Store current player in session
            $_SESSION["mark"] = "X";      // First player always gets 'X'
            $output["status"] = "$first goes first (X)";   // Update status message
            $output["success"] = true;    // Indicate success
            $output["board"] = $_SESSION["board"];    // Return the initialized board
        }
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
 * FunctionName:    victoryCheck
 * Inputs:          $board - 2D array representing the tic-tac-toe board
 *                  $mark  - 'X' or 'O' representing the player's mark
 * Outputs:         Boolean - true if the player with the given mark has won, false otherwise
 * Decription:      Checks if a player has won the game.
 */
function victoryCheck($board, $mark)
{
    // Check rows
    for ($r = 0; $r < 3; $r++) {
        // Check columns
        if (
            $board[$r][0] == $mark &&
            $board[$r][1] == $mark &&
            $board[$r][2] == $mark
        ) {
            return true;  // Player with $mark has won
        }
    }

    // Check columns
    for ($c = 0; $c < 3; $c++) {
        // Check rows
        if (
            $board[0][$c] == $mark &&
            $board[1][$c] == $mark &&
            $board[2][$c] == $mark
        ) {
            return true;  // Player with $mark has won
        }
    }
    // Check diagonals
    if (
        $board[0][0] == $mark &&
        $board[1][1] == $mark &&
        $board[2][2] == $mark
    ) {
        return true;  // Player with $mark has won
    }
    // Check anti-diagonal
    if (
        $board[0][2] == $mark &&
        $board[1][1] == $mark &&
        $board[2][0] == $mark
    ) {
        return true; // Player with $mark has won
    }
    return false;  // No winner found
}

/**
 * FunctionName:    nullGame
 * Inputs:          $board - 2D array representing the tic-tac-toe board
 * Outputs:         Boolean - true if the board is full (draw), false otherwise
 * Decription:      Checks if the board is full (draw).
 */
function nullGame($board)
{
    // Check if all cells are filled
    foreach ($board as $row) {
        // Check each cell in the row
        foreach ($row as $cell) {
            // If any cell is empty, the game is not a draw
            if ($cell == "") {
                return false;  // Found an empty cell, not a draw
            }
        }
    }
    return true;  // The board is full, it's a draw
}

function playMove()
{
    //error_log("In playMove()");
    global $output, $clean;

    $row = intval($clean["row"] ?? -1);         // Get and sanitize row input
    $col = intval($clean["col"] ?? -1);        // Get and sanitize column input

    // Validate row and column inputs
    if ($row < 0 || $row > 2 || $col < 0 || $col > 2) {
        $output["status"] = "Invalid move";        // Set error message
        $output["board"] = $_SESSION["board"];    // Return the current board
        return;
    }

    // Check if the game is already over
    if (!empty($_SESSION["gameOver"])) {
        $output["status"] = "Game is already over. Start a new game.";   // Set error message
        $output["board"] = $_SESSION["board"];    // Return the current board
        $output["gameOver"] = true;      // Indicate game over status

    }

    // Ensure both players and current player are set
    if (!isset($_SESSION["player1"]) || !isset($_SESSION["player2"]) || !isset($_SESSION["currentPlayer"])) {
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
    $_SESSION["board"][$row][$col] = $mark;            // Place the mark on the board
    if (victoryCheck($_SESSION["board"], $mark)) {
        $output["status"] = $_SESSION["currentPlayer"] . " wins!";  // Set victory message
        $_SESSION["gameOver"] = true;   // Mark the game as over
        $output["gameOver"] = true;    // Indicate game over status
        $output["board"] = $_SESSION["board"];     // Return the current board
        return;
    }
    if (nullGame($_SESSION["board"])) {
        $_SESSION["gameOver"] = true;   // Mark the game as over
        $output["status"] = "Game is a draw!";    // Set draw message
        $output["gameOver"] = true;    // Indicate game over status
        $output["board"] = $_SESSION["board"];    // Return the current board
        return;
    }
    //error_log(json_encode($output));


    // Check for victory


    // Check for draw


    $_SESSION["mark"] = ($_SESSION["mark"] == "X" ? "O" : "X");   // Switch marks for next player
    $_SESSION["currentPlayer"] = ($_SESSION["currentPlayer"] == $_SESSION["player1"] ? $_SESSION["player2"] : $_SESSION["player1"]); // Switch current player

    $output["board"] = $_SESSION["board"];   // Return the updated board
    $output["status"] = $_SESSION["currentPlayer"] . "'s turn";  // Update status message

}