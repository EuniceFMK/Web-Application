let selectedRow = null;
let selectedCol = null;

$(document).ready(() => {

    createBoard();

    $("#doubleTurnBtn").hide();
    $("#reviewBtn").hide();

    $("#newGameBtn").click(startGame);
    $("#quitGameBtn").click(quitGame);

    $("#doubleTurnBtn").click(() => {
        CallAjax(
            "https://localhost:7072/doubleTurn",
            "POST",
            {},
            "json",
            (res) => {
                $("#message").text(res.message);
                $("#turnLabel").text("Turn: " + res.currentPlayer);
            },
            ajaxError
        );
    });

    $("#reviewBtn").click(() => {

        CallAjax(
            "https://localhost:7072/getMoves",
            "GET",
            {},
            "json",
            (moves) => {

                let i = 0;

                let interval = setInterval(() => {

                    if (i >= moves.length) {
                        clearInterval(interval);
                        return;
                    }

                    let move = moves[i];

                    $("#message").text(`${move.player}: ${move.from} → ${move.to}`);

                    i++;

                }, 800);
            },
            ajaxError
        );
    });

});

function createBoard() {
    let board = $("#board");
    board.empty();

    for (let row = 0; row < 8; row++) {
        for (let col = 0; col < 8; col++) {

            let color = (row + col) % 2 === 0 ? "white" : "black";

            let cell = $("<div>")
                .addClass("cell")
                .addClass(color)
                .attr("data-row", row)
                .attr("data-col", col);

            cell.click(() => cellClicked(row, col));

            board.append(cell);
        }
    }
}

function startGame() {

    let player1 = $("#player1").val();
    let player2 = $("#player2").val();

    if (player1.trim() === "" || player2.trim() === "") {
        alert("Please enter names for both players.");
        return;
    }

    CallAjax(
        "https://localhost:7072/newGame",
        "POST",
        {
            Player1: player1,
            Player2: player2
        },
        "json",
        loadNewGame,
        ajaxError
    );
}

function loadNewGame(response) {

    $("#message").text(response.message);
    $("#turnLabel").text("Turn: " + response.currentPlayer);

    $("#newGameBtn").prop("disabled", true);
    $("#doubleTurnBtn").show();

    renderPieces(response.board);
}

function renderPieces(boardData) {

    $(".cell").empty();

    for (let row = 0; row < 8; row++) {
        for (let col = 0; col < 8; col++) {

            let value = boardData[row][col];

            if (value !== "") {

                let piece = $("<div>").addClass("piece");

                if (value === "A" || value === "AK") {
                    piece.addClass("red");
                }

                if (value === "B" || value === "BK") {
                    piece.addClass("blue");
                }

                if (value === "AK" || value === "BK") {
                    piece.append("K");
                    piece.addClass("king");
                }

                $(`.cell[data-row=${row}][data-col=${col}]`)
                    .append(piece);
            }
        }
    }
}

function cellClicked(row, col) {

    if (selectedRow === row && selectedCol === col) {
        selectedRow = null;
        selectedCol = null;
        $("#message").text("Selection cleared");
        return;
    }

    if (selectedRow === null) {
        selectedRow = row;
        selectedCol = col;
        $("#message").text(`Selected piece at (${row}, ${col})`);
        return;
    }

    CallAjax(
        "https://localhost:7072/movePiece",
        "POST",
        {
            fromRow: selectedRow,
            fromCol: selectedCol,
            toRow: row,
            toCol: col
        },
        "json",
        loadMove,
        ajaxError
    );

    selectedRow = null;
    selectedCol = null;
}

function loadMove(response) {

    $("#message").text(response.message);
    renderPieces(response.board);

    $("#turnLabel").text("Turn: " + response.currentPlayer);

    if (response.doubleJump) {
        $("#doubleTurnBtn").show();
    } else {
        $("#doubleTurnBtn").hide();
    }

    if (response.winner !== "") {
        $("#message").text(`Winner is ${response.winner}`);
        $("#reviewBtn").show();
        $("#doubleTurnBtn").hide();
    }
}

function quitGame() {

    CallAjax(
        "https://localhost:7072/quitGame",
        "POST",
        {},
        "json",
        () => {
            location.reload();
        },
        ajaxError
    );
}

/* ================= AJAX ================= */

function CallAjax(url, method, data, dataType, successCallback, errorCallback) {

    let ajaxOptions = {};
    ajaxOptions.url = url;
    ajaxOptions.method = method;

    if (method === "POST" || method === "PUT") {
        ajaxOptions.data = JSON.stringify(data);
        ajaxOptions.contentType = "application/json";
    } else {
        ajaxOptions.data = data;
    }

    ajaxOptions.dataType = dataType;
    ajaxOptions.success = successCallback;
    ajaxOptions.error = errorCallback;

    return $.ajax(ajaxOptions);
}

function ajaxError(req, status, err) {
    console.log("Ajax Error", req.status, status, err);

    if (req.responseJSON && req.responseJSON.message) {
        $("#message").text(req.responseJSON.message);
    }
}