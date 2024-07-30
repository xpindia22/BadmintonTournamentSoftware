const poolATeams = [];
const poolBTeams = [];
const quarterFinalsPoolA = [];
const quarterFinalsPoolB = [];
const semiFinalsPoolA = [];
const semiFinalsPoolB = [];
const finalsMatch = [];

// Fetch players from database
fetch('https://example.com/badminton-players')
  .then(response => response.json())
  .then(players => {
    players.forEach(player => {
      if (player.pool === 'A') {
        poolATeams.push(player);
      } else {
        poolBTeams.push(player);
      }
    });
  });

// Create fixtures
document.getElementById('create-fixtures').addEventListener('click', () => {
  // Create qualifying rounds fixtures
  const qualifyingRoundsFixtures = [];
  poolATeams.forEach((team, index) => {
    qualifyingRoundsFixtures.push({
      teamA: team,
      teamB: poolATeams[(index + 1) % poolATeams.length],
    });
  });
  poolBTeams.forEach((team, index) => {
    qualifyingRoundsFixtures.push({
      teamA: team,
      teamB: poolBTeams[(index + 1) % poolBTeams.length],
    });
  });

  // Create quarter finals fixtures
  const quarterFinalsFixtures = [];
  quarterFinalsPoolA.forEach((team, index) => {
    quarterFinalsFixtures.push({
      teamA: team,
      teamB: quarterFinalsPoolA[(index + 1) % quarterFinalsPoolA.length],
    });
  });
  quarterFinalsPoolB.forEach((team, index) => {
    quarterFinalsFixtures.push({
      teamA: team,
      teamB: quarterFinalsPoolB[(index + 1) % quarterFinalsPoolB.length],
    });
  });

  // Create semi finals fixtures
  const semiFinalsFixtures = [];
  semiFinalsPoolA.forEach((team, index) => {
    semiFinalsFixtures.push({
      teamA: team,
      teamB: semiFinalsPoolA[(index + 1) % semiFinal