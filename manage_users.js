$(document).ready(function () {
    // Trigger search on keyup in the search bar
    $('#searchUser').on('keyup', function () {
        var searchTerm = $(this).val();

        // Call the PHP script to fetch search results
        $.ajax({
            type: 'GET',
            url: 'search_users.php',
            data: { searchTerm: searchTerm },
            success: function (data) {
                $('#searchResults').html(data);

                // Add click event to the Select button
                $('.selectUserBtn').click(function () {
                    var selectedUsername = $(this).data('username');

                    // Open the modal with user actions and details
                    openUserActionsModal(selectedUsername);
                });
            }
        });
    });

    // Function to open the modal with user actions and details
    function openUserActionsModal(username) {
        // Call the PHP script to fetch user details
        $.ajax({
            type: 'GET',
            url: 'getUserDetails.php',
            data: { username: username },
            success: function (userData) {
                // Customize the modal content based on your requirements
                var modalContent = '<div class="modal-dialog">';
                modalContent += '    <div class="modal-content">';
                modalContent += '        <div class="modal-header">';
                modalContent += '            <h5 class="modal-title">Actions for ' + username + '</h5>';
                modalContent += '            <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                modalContent += '                <span aria-hidden="true">&times;</span>';
                modalContent += '            </button>';
                modalContent += '        </div>';
                modalContent += '        <div class="modal-body">';
                modalContent += '            <p><strong>User ID:</strong> ' + userData.user_id + '</p>';
                modalContent += '            <p><strong>First Name:</strong> ' + userData.first_name + '</p>';
                modalContent += '            <p><strong>Last Name:</strong> ' + userData.last_name + '</p>';
                modalContent += '            <p><strong>Email:</strong> ' + userData.email + '</p>';
                modalContent += '            <p><strong>Role:</strong> ' + userData.role + '</p>';
                // Add your form or buttons for user actions here
                modalContent += '            <button class="btn btn-danger" onclick="deleteUser(\'' + username + '\')">Delete User</button>';
                modalContent += '            <button class="btn btn-primary" onclick="changePassword(\'' + username + '\')">Change Password</button>';
                modalContent += '            <button class="btn btn-success" onclick="promoteUser(\'' + username + '\')">Promote User</button>';
                modalContent += '        </div>';
                modalContent += '    </div>';
                modalContent += '</div>';

                // Set the modal content and show the modal
                $('#userActionsModal').html(modalContent);
                $('#userActionsModal').modal('show');
            }
        });
    }
});

// Functions for user actions
function deleteUser(username) {
    // Implement your logic for deleting the user
    alert('Deleting user: ' + username);
}

function changePassword(username) {
    // Implement your logic for changing the user's password
    alert('Changing password for user: ' + username);
}

function promoteUser(username) {
    // Implement your logic for promoting the user
    alert('Promoting user: ' + username);
}
