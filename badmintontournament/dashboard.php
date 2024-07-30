<?php
require_once 'functions.php'; // Ensure this file is included to use functions

// Fetch players and matches
$round1Matches = getMatchesByRound($conn, 1);
$quarterFinals = getMatchesByRound($conn, 3);
$semiFinals = getMatchesByRound($conn, 4);
$finals = getMatchesByRound($conn, 5);
$progressions = getPlayerProgressions($conn);
$losers = getPlayerLosers($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
        }
        .round {
            flex: 1;
            margin: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            box-sizing: border-box;
            min-width: 300px;
        }
        .player {
            margin: 5px 0;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .winner {
            background-color: #d4edda; /* Light green */
        }
        .loser {
            background-color: #f8d7da; /* Light red */
        }
        .arrow {
            font-size: 24px;
            text-align: center;
            margin: 10px 0;
        }
        h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Badminton Tournament Dashboard</h1>
    </header>

    <div class="container">
        <!-- Round 1 -->
        <div class="round">
            <h2>Round 1</h2>
            <?php if (!empty($round1Matches)): ?>
                <?php foreach ($round1Matches as $match): ?>
                    <div class="match">
                        <h3>Match <?php echo htmlspecialchars($match['match_id']); ?></h3>
                        <div class="player <?php echo $match['winner_id'] == 1 ? 'winner' : 'loser'; ?>">
                            <strong><?php echo htmlspecialchars($match['player1']); ?></strong><br>
                            Set 1: <?php echo htmlspecialchars($match['player1_set1']); ?> | 
                            Set 2: <?php echo htmlspecialchars($match['player1_set2']); ?> | 
                            Set 3: <?php echo htmlspecialchars($match['player1_set3']); ?>
                        </div>
                        <div class="player <?php echo $match['winner_id'] == 2 ? 'winner' : 'loser'; ?>">
                            <strong><?php echo htmlspecialchars($match['player2']); ?></strong><br>
                            Set 1: <?php echo htmlspecialchars($match['player2_set1']); ?> | 
                            Set 2: <?php echo htmlspecialchars($match['player2_set2']); ?> | 
                            Set 3: <?php echo htmlspecialchars($match['player2_set3']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No matches available for Round 1.</p>
            <?php endif; ?>
        </div>

        <!-- Arrow to next round -->
        <div class="arrow">
            &rarr;
        </div>

        <!-- Quarter Finals -->
        <div class="round">
            <h2>Quarter Finals</h2>
            <?php if (!empty($quarterFinals)): ?>
                <?php foreach ($quarterFinals as $match): ?>
                    <div class="match">
                        <h3>Match <?php echo htmlspecialchars($match['match_id']); ?></h3>
                        <div class="player <?php echo $match['winner_id'] == 1 ? 'winner' : 'loser'; ?>">
                            <strong><?php echo htmlspecialchars($match['player1']); ?></strong><br>
                            Set 1: <?php echo htmlspecialchars($match['player1_set1']); ?> | 
                            Set 2: <?php echo htmlspecialchars($match['player1_set2']); ?> | 
                            Set 3: <?php echo htmlspecialchars($match['player1_set3']); ?>
                        </div>
                        <div class="player <?php echo $match['winner_id'] == 2 ? 'winner' : 'loser'; ?>">
                            <strong><?php echo htmlspecialchars($match['player2']); ?></strong><br>
                            Set 1: <?php echo htmlspecialchars($match['player2_set1']); ?> | 
                            Set 2: <?php echo htmlspecialchars($match['player2_set2']); ?> | 
                            Set 3: <?php echo htmlspecialchars($match['player2_set3']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No matches available for Quarter Finals.</p>
            <?php endif; ?>
        </div>

        <!-- Arrow to next round -->
        <div class="arrow">
            &rarr;
        </div>

        <!-- Semi Finals -->
        <div class="round">
            <h2>Semi Finals</h2>
            <?php if (!empty($semiFinals)): ?>
                <?php foreach ($semiFinals as $match): ?>
                    <div class="match">
                        <h3>Match <?php echo htmlspecialchars($match['match_id']); ?></h3>
                        <div class="player <?php echo $match['winner_id'] == 1 ? 'winner' : 'loser'; ?>">
                            <strong><?php echo htmlspecialchars($match['player1']); ?></strong><br>
                            Set 1: <?php echo htmlspecialchars($match['player1_set1']); ?> | 
                            Set 2: <?php echo htmlspecialchars($match['player1_set2']); ?> | 
                            Set 3: <?php echo htmlspecialchars($match['player1_set3']); ?>
                        </div>
                        <div class="player <?php echo $match['winner_id'] == 2 ? 'winner' : 'loser'; ?>">
                            <strong><?php echo htmlspecialchars($match['player2']); ?></strong><br>
                            Set 1: <?php echo htmlspecialchars($match['player2_set1']); ?> | 
                            Set 2: <?php echo htmlspecialchars($match['player2_set2']); ?> | 
                            Set 3: <?php echo htmlspecialchars($match['player2_set3']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No matches available for Semi Finals.</p>
            <?php endif; ?>
        </div>

        <!-- Arrow to next round -->
        <div class="arrow">
            &rarr;
        </div>

        <!-- Finals -->
        <div class="round">
            <h2>Finals</h2>
            <?php if (!empty($finals)): ?>
                <?php foreach ($finals as $match): ?>
                    <div class="match">
                        <h3>Match <?php echo htmlspecialchars($match['match_id']); ?></h3>
                        <div class="player <?php echo $match['winner_id'] == 1 ? 'winner' : 'loser'; ?>">
                            <strong><?php echo htmlspecialchars($match['player1']); ?></strong><br>
                            Set 1: <?php echo htmlspecialchars($match['player1_set1']); ?> | 
                            Set 2: <?php echo htmlspecialchars($match['player1_set2']); ?> | 
                            Set 3: <?php echo htmlspecialchars($match['player1_set3']); ?>
                        </div>
                        <div class="player <?php echo $match['winner_id'] == 2 ? 'winner' : 'loser'; ?>">
                            <strong><?php echo htmlspecialchars($match['player2']); ?></strong><br>
                            Set 1: <?php echo htmlspecialchars($match['player2_set1']); ?> | 
                            Set 2: <?php echo htmlspecialchars($match['player2_set2']); ?> | 
                            Set 3: <?php echo htmlspecialchars($match['player2_set3']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No matches available for Finals.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
