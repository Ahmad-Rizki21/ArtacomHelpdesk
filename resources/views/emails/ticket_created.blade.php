<!DOCTYPE html>
<html>
<head>
    <title>New Ticket Created</title>
</head>
<body>
    <h1>New Ticket Created</h1>
    <p>A new ticket has been created with the following details:</p>
    <p><strong>Title:</strong> {{ $ticket->title }}</p>
    <p><strong>Description:</strong> {{ $ticket->description }}</p>
    <p><strong>Status:</strong> {{ $ticket->status }}</p>
    <p>Please log in to the helpdesk system to view and handle this ticket.</p>
</body>
</html>