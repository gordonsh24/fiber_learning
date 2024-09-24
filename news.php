<?php
// Simulate a delay in fetching data
sleep(3);  // Simulating a 3-second delay

// Return JSON response
echo json_encode([
  'headlines' => [
    'PHP 8.1 Released with Fibers!',
    'World Leaders Discuss Climate Change',
    'New Innovations in AI for 2024'
  ]
]);