/*Programmer Block
    Name: Eunice Fmukam Ngadjou
    Class: CMPE 2550 Web Application Development    
    Date: January 20, 2025
    Assignment: ICA 2 - Tic Tac Toe Game
    Description: JavaScript code to handle the Tic Tac Toe game logic and AJAX interactions.
*/
let gameOver = false;           // Flag to track if the game is over
$(document).ready(function () {
    
    CallAjax("gameflow.php", "GET", { action: "InitGame" }, "json", initSuccess, err);  // Initialize the game on page load
    $("#nw").click(newGame);    // Start a new game when "New Game" button is clicked

    $("#qg").click(function () {
        gameOver = false;  // Reset game over flag
        $("#p1").val("");  // Clear player 1 name input
        $("#p2").val("");   // Clear player 2 name input
        $("#names").html("Enter your names below");    //Prompt for player names
        $("#board").hide();                    // Hide the game board
    })

    $("#board input").click(function () {
        if (gameOver) {
            alert("Game is already over. Start a new game.");  // Alert if game is over
            console.log(gameOver);  // Log game over status
            return;
        }
        let id = $(this).attr("id");    // Get the ID of the clicked cell
        let parts = id.split("_");     // Split the ID to get row and column
        let row = parts[0];           // Extract row index
        let col = parts[1];          // Extract column index
        playMove(row, col);         // Call function to play the move
    })
})
/**
 * FunctionName:    CallAjax
 * Inputs:          url - URL to send the AJAX request to
 *                  method - HTTP method (GET, POST, etc.)
 *                  data - Data to be sent with the request 
 *                  dataType - Expected data type of the response
 *                  successCallback - Function to call on successful response
 *                  errorCallback - Function to call on error response
 * Outputs:         AJAX request object
 * Decription:     Sends an AJAX request with the specified parameters.
 */
function CallAjax(url, method, data, dataType, successCallback, errorCallback) {
    let ajaxOptions = {};
    ajaxOptions["url"] = url;
    ajaxOptions["method"] = method;
    ajaxOptions["data"] = data;
    ajaxOptions["dataType"] = dataType;
    ajaxOptions["success"] = successCallback;
    ajaxOptions["error"] = errorCallback;
    console.log(ajaxOptions[data]);
    return $.ajax(ajaxOptions);
}
/**
 * FunctionName:    newGame
 * Inputs:          None
 * Outputs:         None
 * Decription:      Starts a new game by resetting the board and player names.
 */
function newGame() {
    gameOver = false;    // Reset game over flag
    $("#board").removeClass("disabled");   // Enable the game board
    $("#board input").val("");   // Clear the game board

    // Send AJAX request to start a new game
    CallAjax(
        "gameflow.php",
        "GET",
        {
            action: "NewGame",
            player1: $("#p1").val(),
            player2: $("#p2").val()
        },
        "json",
        success,
        err);
    $("#board").show();
}
/**
 * FunctionName:    nullGame
 * Inputs:          $board - 2D array representing the tic-tac-toe board
 * Outputs:         Boolean - true if the board is full (draw), false otherwise
 * Decription:      Checks if the board is full (draw).
 */
function success(response) {
    
    console.log(response);
    if (response.board) {
        for (let r = 0; r < 3; r++) {
            for (let c = 0; c < 3; c++) {
                let cell = $("#" + r + "_" + c);
                cell.removeClass("xCell oCell");  // Clear existing classes
                cell.val(response.board[r][c]);  // Set cell value
                if (response.board[r][c] === "X") {
                    cell.addClass("xCell");    // Add class for X
                }
                else if (response.board[r][c] === "O") {
                    cell.addClass("oCell");   // Add class for O
                }
            }
        }
    }
    //$("#table td").text("");
    $("#names").html(response.status);
    if (response.success) {
        $("#board").show();   // Show the game board
    }
    if (response.gameOver) {
        gameOver = true;
        $("#board").addClass("disabled");   // Disable the game board
    }
}
/**
 * FunctionName:    nullGame
 * Inputs:          $board - 2D array representing the tic-tac-toe board
 * Outputs:         Boolean - true if the board is full (draw), false otherwise
 * Decription:      Checks if the board is full (draw).
 */
function err(response) {
    alert("An error occured");  // Alert on AJAX error
}
/**
 * FunctionName:    nullGame
 * Inputs:          $board - 2D array representing the tic-tac-toe board
 * Outputs:         Boolean - true if the board is full (draw), false otherwise
 * Decription:      Checks if the board is full (draw).
 */
function initSuccess(response) {
    console.log("Game Initilaized", response);
}
/**
 * FunctionName:    nullGame
 * Inputs:          $board - 2D array representing the tic-tac-toe board
 * Outputs:         Boolean - true if the board is full (draw), false otherwise
 * Decription:      Checks if the board is full (draw).
 */
function playMove(row, col) {
    console.log("In playMove");
let data =
{
            action: "PlayMove",
            row: row,
            col: col
        }
console.log(data);
    // Send AJAX request to play the move
    CallAjax(
        "gameflow.php",
        "GET",
        data,
        "json",
        updateBoard,
        err
    );
}
/**
 * FunctionName:    updateBoard
 * Inputs:          response - JSON response from the server containing the updated game state
 * Outputs:         None
 * Decription:      Updates the game board and status based on the server response.
 */
function updateBoard(response) {
    console.log(response);
    $("#names").text(response.status);  // Update game status message
    
    // Update the board based on the response
    if (response.board) {
        // Iterate through the board and update each cell
        for (let r = 0; r < 3; r++) {
            for (let c = 0; c < 3; c++) {
                let cell = $("#" + r + "_" + c);   // Select the cell by its ID
                cell.removeClass("xCell oCell");   // Remove existing classes
                cell.val(response.board[r][c]);   // Set the cell value
                if (response.board[r][c] === "X") {
                    cell.addClass("xCell");   // Add class for X
                }
                else if (response.board[r][c] === "O") {
                    cell.addClass("oCell");   // Add class for O
                }
            }
        }
    }

    // Check if the game is over
    if (response.gameOver == true) {
        gameOver = true;  //    Set game over flag
        $("#board").addClass("disabled");  // Disable the game board
    }
}