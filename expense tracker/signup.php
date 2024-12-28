<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php'; 

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header('Location: login.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f7fc;
            font-family: 'Poppins', sans-serif;
            background-image: url('https://www.streebo.com/wp-content/themes/streebo/images/LangingPage/Expense-Management-Cloud-DXA/Expense-Management-Cloud-DXA-banner.jpg');
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-box {
            display: flex;
            justify-content: space-between;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 70%;
        }

        .signup-image {
            flex: 1;
            background-image: url('https://cdni.iconscout.com/illustration/premium/thumb/expense-management-illustration-download-in-svg-png-gif-file-formats--business-finance-strategy-concept-pack-illustrations-3561009.png'); /* Replace with your image path */
            background-size: cover;
            /* background-position: center; */
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .signup-form {
            flex: 1;
            padding: 40px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .signup-form h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .btn-primary {
            background-color: #2575fc;
            border: none;
        }

        .btn-primary:hover {
            background-color: #6a11cb;
        }

        .error {
            color: red;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .signup-box {
                flex-direction: column;
                width: 100%;
                border-radius: 10px;
            }

            .signup-image {
                border-radius: 10px 10px 0 0;
                height: 200px;
            }

            .signup-form {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="signup-box">
        
        <div class="signup-image"></div>

        
        <div class="signup-form">
            <h2 class="text-center mb-4">Create an Account</h2>
            <?php if (isset($error)): ?>
                <div class="error mb-3"><?= $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                <p class="text-center mt-3">Already have an account? <a href="login.php" class="text-primary">Login</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
