<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Generate Tickets</title>
</head>
<body>
  <h3>Generate Tickets</h3>
  <form action="generate.php" method="post">
    Name of the event: 
    <input type="text" name="event_name" placeholder="Event name" required> <br>
    Venue details: <br>
    <textarea name="venue" cols="30" rows="10" required></textarea> <br>
    Number of tickets to generate:
    <input type="number" name="number_of_tickets" placeholder="Enter positive" required> <br>
    <input type="submit" value="Generate" name="generatepdf"> <input type="submit" value="Export IDs as .txt" name="export">
  </form>
</body>
</html>