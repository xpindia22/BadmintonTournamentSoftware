<?php
require_once 'functions.php';

// Fetch data for all rounds
$round1Matches = getMatchesByRound($conn, 1);
$quarterFinals = getMatchesByRound($conn, 3);
$semiFinals = getMatchesByRound($conn, 4);
$finals = getMatchesByRound($conn, 5);
$playerProgressions = getPlayerProgressions($conn);
$playerLosers = getPlayerLosers($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badminton Tournament Dashboard</title>
    <style>
        .dashboard {
            display: flex;
            flex-direction: row;
            justify-content: space-around;
        }
        .round {
            margin: 0 10px;
            padding: 10px;
        }
        .round h2 {
            text-align: center;
        }
        .match {
            margin-bottom: 10px;
        }
        .winner {
            background-color: #d4edda; /* Light green */
            padding: 5px;
            border-radius: 5px;
            display: inline-block;
        }
        .loser {
            background-color: #f8d7da; /* Light red */
            padding: 5px;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <header>
        <h1>Badminton Tournament Dashboard</h1>
    </header>
    <div class="dashboard">
        <!-- Round 1 -->
        <div class="round round1">
            <h2>Round 1</h2>
            <?php foreach ($round1Matches as $match): ?>
                <div class="match">
                    <span class="winner"><?php echo htmlspecialchars($match['player1']); ?></span> vs. 
                    <span class="winner"><?php echo htmlspecialchars($match['player2']); ?></span>
                    <div>
                        <?php 
                            $scores = [
                                htmlspecialchars($match['player1_set1']) . '-' . htmlspecialchars($match['player2_set1']),
                                htmlspecialchars($match['player1_set2']) . '-' . htmlspecialchars($match['player2_set2']),
                                htmlspecialchars($match['player1_set3']) . '-' . htmlspecialchars($match['player2_set3'])
                            ];
                            echo implode(', ', $scores); 
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Quarter Finals -->
        <div class="round quarter-finals">
            <h2>Quarter Finals</h2>
            <?php foreach ($quarterFinals as $match): ?>
                <div class="match">
                    <span class="winner"><?php echo htmlspecialchars($match['player1']); ?></span> vs. 
                    <span class="winner"><?php echo htmlspecialchars($match['player2']); ?></span>
                    <div>
                        <?php 
                            $scores = [
                                htmlspecialchars($match['player1_set1']) . '-' . htmlspecialchars($match['player2_set1']),
                                htmlspecialchars($match['player1_set2']) . '-' . htmlspecialchars($match['player2_set2']),
                                htmlspecialchars($match['player1_set3']) . '-' . htmlspecialchars($match['player2_set3'])
                            ];
                            echo implode(', ', $scores); 
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Semi Finals -->
        <div class="round semi-finals">
            <h2>Semi Finals</h2>
            <?php foreach ($semiFinals as $match): ?>
                <div class="match">
                    <span class="winner"><?php echo htmlspecialchars($match['player1']); ?></span> vs. 
                    <span class="winner"><?php echo htmlspecialchars($match['player2']); ?></span>
                    <div>
                        <?php 
                            $scores = [
                                htmlspecialchars($match['player1_set1']) . '-' . htmlspecialchars($match['player2_set1']),
                                htmlspecialchars($match['player1_set2']) . '-' . htmlspecialchars($match['player2_set2']),
                                htmlspecialchars($match['player1_set3']) . '-' . htmlspecialchars($match['player2_set3'])
                            ];
                            echo implode(', ', $scores); 
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Finals -->
        <div class="round finals">
            <h2>Finals</h2>
            <?php foreach ($finals as $match): ?>
                <div class="match">
                    <span class="winner"><?php echo htmlspecialchars($match['player1']); ?></span> vs. 
                    <span class="winner"><?php echo htmlspecialchars($match['player2']); ?></span>
                    <div>
                        <?php 
                            $scores = [
                                htmlspecialchars($match['player1_set1']) . '-' . htmlspecialchars($match['player2_set1']),
                                htmlspecialchars($match['player1_set2']) . '-' . htmlspecialchars($match['player2_set2']),
                                htmlspecialchars($match['player1_set3']) . '-' . htmlspecialchars($match['player2_set3'])
                            ];
                            echo implode(', ', $scores); 
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
