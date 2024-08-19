<!DOCTYPE html>
<html>
<head>
    <title>Vacation Plan</title>
</head>
<body>
    <h1>{{ $plan->title }}</h1>
    <p><strong>Description:</strong> {{ $plan->description }}</p>
    <p><strong>Date:</strong> {{ $plan->date }}</p>
    <p><strong>Location:</strong> {{ $plan->location }}</p>
    <p><strong>Participants:</strong> {{ $plan->participants }}</p>
</body>
</html>
