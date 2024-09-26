<?php
// Simulate a delay in fetching data
sleep(2);  // Simulating a 2-second delay

// Return JSON response
echo json_encode([
  'temperature' => '28°C',
  'condition' => 'Sunny',
  'location' => 'Manila'
]);