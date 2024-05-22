<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Create a New Account</h2>
        <div id="error-message" class="error-message"></div>
        <form id="create-account-form", enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age">
            </div>
            <button type="submit">Create Account</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#create-account-form').on('submit', function(event) {
                event.preventDefault();
                var formData = {
                    username: $('#username').val(),
                    password: $('#password').val(),
                    age: $('#age').val()
                };

                $.ajax({
                    type: 'POST',
                    url: 'account/create.php',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function(response) {
                        $('#error-message').text('You just created an account').show();
                        document.getElementById('error-message').style.backgroundColor = 'green'; 
                        $('#create-account-form')[0].reset();
                        // $('#error-message').text('').hide();
                    },
                    error: function(xhr) {
                        try {
                            var errorMessage = JSON.parse(xhr.responseText);
                            $('#error-message').text(errorMessage.message.join(', ')).show();
                            document.getElementById('error-message').style.backgroundColor = 'red';
                        } catch (e) {
                            $('#error-message').text('An unexpected error occurred. Please try again.').show();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
