<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">;
</head>
<body>
<div class="container mt-4">
    <h2>Manage Users</h2>

    <!-- Search Bar -->
    <input type="text" id="searchUser" class="form-control mb-3" placeholder="Search for a user">

    <!-- Display Search Results -->
    <div id="searchResults"></div>

    <!-- Modal for User Actions -->
    <div class="modal fade" id="userActionsModal" tabindex="-1" aria-labelledby="userActionsModalLabel" aria-hidden="true">
        <!-- Include modal content here -->
        <!-- You can customize the modal based on your requirements -->
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include custom JavaScript -->
    <script src="manage_users.js"></script>
</div>

</body>
</html>
