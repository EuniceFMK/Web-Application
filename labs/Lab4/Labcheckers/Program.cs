using Microsoft.Data.SqlClient;

namespace Labcheckers
{
    public class Program
    {
        static GameState game = new GameState();

        public static void Main(string[] args)
        {
            string connectionString =
                "Server=data.cnt.sast.ca,24680;" +
                "Database=efmukam_CheckersDB;" +
                "User Id=efmukamngadjou1;" +
                "Password=Rachel1980@,.;" +
                "Encrypt=False";

            var builder = WebApplication.CreateBuilder(args);
            builder.Services.AddControllers();
            builder.Services.AddCors();

            var app = builder.Build();

            app.UseCors(x =>
                x.AllowAnyOrigin()
                 .AllowAnyMethod()
                 .AllowAnyHeader());

            app.UseDeveloperExceptionPage();

            app.MapGet("/", () => "Checkers Game Running");

            // ================= NEW GAME =================
            app.MapPost("/newGame", (PlayerInfo inputData) =>
            {
                if (inputData == null ||
                    string.IsNullOrWhiteSpace(inputData.Player1) ||
                    string.IsNullOrWhiteSpace(inputData.Player2))
                {
                    return Results.BadRequest(new { message = "Both player names are required." });
                }

                game = new GameState
                {
                    Player1 = inputData.Player1.Trim(),
                    Player2 = inputData.Player2.Trim(),
                    CurrentPlayer = inputData.Player1.Trim(),
                    NoCaptureMoves = 0,
                    Board = new List<List<string>>()
                };

                for (int r = 0; r < 8; r++)
                {
                    var row = new List<string>();
                    for (int c = 0; c < 8; c++)
                        row.Add("");
                    game.Board.Add(row);
                }

                for (int r = 0; r < 3; r++)
                    for (int c = 0; c < 8; c++)
                        if ((r + c) % 2 != 0)
                            game.Board[r][c] = "A";

                for (int r = 5; r < 8; r++)
                    for (int c = 0; c < 8; c++)
                        if ((r + c) % 2 != 0)
                            game.Board[r][c] = "B";

                return Results.Ok(new
                {
                    message = $"Let's Go {game.CurrentPlayer}",
                    board = game.Board,
                    currentPlayer = game.CurrentPlayer
                });
            });

            // ================= MOVE PIECE =================
            app.MapPost("/movePiece", (MoveInfo move) =>
            {
                if (!IsValid(move))
                    return Results.BadRequest(new { message = "Invalid board position", currentPlayer = game.CurrentPlayer });

                string piece = game.Board[move.fromRow][move.fromCol];

                if (piece == "")
                    return Results.BadRequest(new { message = "No piece selected", currentPlayer = game.CurrentPlayer });

                string expected = game.CurrentPlayer == game.Player1 ? "A" : "B";

                if (piece[0].ToString() != expected)
                    return Results.BadRequest(new { message = "Not your piece", currentPlayer = game.CurrentPlayer });

                if (game.Board[move.toRow][move.toCol] != "")
                    return Results.BadRequest(new { message = "Destination not empty", currentPlayer = game.CurrentPlayer });

                int rowDiff = move.toRow - move.fromRow;
                int colDiff = move.toCol - move.fromCol;

                bool isKing = piece.Contains("K");
                bool doubleJump = false;

                // ================= NORMAL MOVE =================
                if (Math.Abs(rowDiff) == 1 && Math.Abs(colDiff) == 1)
                {
                    if (!isKing && !IsForwardMove(rowDiff))
                        return Results.BadRequest(new { message = "Invalid direction", currentPlayer = game.CurrentPlayer });

                    MovePiece(move, piece);

                    game.NoCaptureMoves++;

                    SwitchTurn();

                    return Results.Ok(Response("Move successful", doubleJump));
                }

                // ================= CAPTURE MOVE =================
                if (Math.Abs(rowDiff) == 2 && Math.Abs(colDiff) == 2)
                {
                    int midRow = (move.fromRow + move.toRow) / 2;
                    int midCol = (move.fromCol + move.toCol) / 2;

                    string middle = game.Board[midRow][midCol];

                    if (middle == "" || middle[0] == piece[0])
                        return Results.BadRequest(new { message = "No enemy piece to capture", currentPlayer = game.CurrentPlayer });

                    MovePiece(move, piece);
                    game.Board[midRow][midCol] = "";

                    game.NoCaptureMoves = 0;

                    doubleJump = CanCaptureAgain(move.toRow, move.toCol);

                    if (!doubleJump)
                        SwitchTurn();

                    return Results.Ok(Response("Capture successful", doubleJump));
                }

                return Results.BadRequest(new { message = "Invalid move", currentPlayer = game.CurrentPlayer });
            });

            // ================= DOUBLE TURN =================
            app.MapPost("/doubleTurn", () =>
            {
                SwitchTurn();

                return Results.Ok(new
                {
                    message = $"Double turn granted to {game.CurrentPlayer}",
                    currentPlayer = game.CurrentPlayer
                });
            });

            // ================= GET MOVES =================
            app.MapGet("/getMoves", () =>
            {
                List<object> moves = new();

                using SqlConnection conn = new SqlConnection(connectionString);
                conn.Open();

                string sql = "SELECT FromPosition, ToPosition, PlayerName FROM GameMoves ORDER BY Id";

                using SqlCommand cmd = new SqlCommand(sql, conn);
                using SqlDataReader reader = cmd.ExecuteReader();

                while (reader.Read())
                {
                    moves.Add(new
                    {
                        from = reader.GetString(0),
                        to = reader.GetString(1),
                        player = reader.GetString(2)
                    });
                }

                return Results.Ok(moves);
            });

            // ================= QUIT =================
            app.MapPost("/quitGame", () =>
            {
                game = new GameState();
                return Results.Ok(new { message = "Game reset" });
            });

            // ================= HELPERS =================
            bool IsValid(MoveInfo m) =>
                m.fromRow >= 0 && m.fromRow < 8 &&
                m.toRow >= 0 && m.toRow < 8 &&
                m.fromCol >= 0 && m.fromCol < 8 &&
                m.toCol >= 0 && m.toCol < 8;

            bool IsForwardMove(int rowDiff) =>
                (game.CurrentPlayer == game.Player1 && rowDiff == 1) ||
                (game.CurrentPlayer == game.Player2 && rowDiff == -1);

            void MovePiece(MoveInfo move, string piece)
            {
                game.Board[move.toRow][move.toCol] = piece;
                game.Board[move.fromRow][move.fromCol] = "";
                CheckKing(move.toRow, move.toCol);

                SaveMove(game.CurrentPlayer,
                    move.fromRow, move.fromCol,
                    move.toRow, move.toCol);
            }

            void SwitchTurn()
            {
                game.CurrentPlayer =
                    game.CurrentPlayer == game.Player1 ? game.Player2 : game.Player1;
            }

            object Response(string msg, bool doubleJump) => new
            {
                message = msg,
                board = game.Board,
                doubleJump,
                winner = CheckWinner(),
                currentPlayer = game.CurrentPlayer
            };

            void CheckKing(int row, int col)
            {
                if (game.Board[row][col] == "A" && row == 7)
                    game.Board[row][col] = "AK";

                if (game.Board[row][col] == "B" && row == 0)
                    game.Board[row][col] = "BK";
            }

            bool CanCaptureAgain(int row, int col)
            {
                int[] dirs = { -2, 2 };
                string piece = game.Board[row][col];

                foreach (int r in dirs)
                    foreach (int c in dirs)
                    {
                        int nr = row + r;
                        int nc = col + c;

                        if (nr < 0 || nr > 7 || nc < 0 || nc > 7)
                            continue;

                        int mr = (row + nr) / 2;
                        int mc = (col + nc) / 2;

                        if (game.Board[nr][nc] == "" &&
                            game.Board[mr][mc] != "" &&
                            game.Board[mr][mc][0] != piece[0])
                            return true;
                    }

                return false;
            }

            string CheckWinner()
            {
                int a = 0, b = 0;

                for (int r = 0; r < 8; r++)
                    for (int c = 0; c < 8; c++)
                    {
                        if (game.Board[r][c] == "A" || game.Board[r][c] == "AK") a++;
                        if (game.Board[r][c] == "B" || game.Board[r][c] == "BK") b++;
                    }

                if (a == 0) return game.Player2;
                if (b == 0) return game.Player1;
                if (game.NoCaptureMoves >= 30) return "Draw";

                return "";
            }

            void SaveMove(string player, int fr, int fc, int tr, int tc)
            {
                try
                {
                    using SqlConnection conn = new SqlConnection(connectionString);

                    string sql = @"INSERT INTO GameMoves
                    (GameId, PlayerName, FromPosition, ToPosition)
                    VALUES (@id, @p, @f, @t)";

                    using SqlCommand cmd = new SqlCommand(sql, conn);

                    cmd.Parameters.AddWithValue("@id", 1);
                    cmd.Parameters.AddWithValue("@p", player);
                    cmd.Parameters.AddWithValue("@f", $"{fr},{fc}");
                    cmd.Parameters.AddWithValue("@t", $"{tr},{tc}");

                    conn.Open();
                    cmd.ExecuteNonQuery();
                }
                catch { }
            }

            app.Run();
        }

        record PlayerInfo(string Player1, string Player2);
        record MoveInfo(int fromRow, int fromCol, int toRow, int toCol);
    }

    class GameState
    {
        public string Player1 { get; set; }
        public string Player2 { get; set; }
        public string CurrentPlayer { get; set; }
        public List<List<string>> Board { get; set; } = new();
        public int NoCaptureMoves { get; set; } = 0;
    }
}