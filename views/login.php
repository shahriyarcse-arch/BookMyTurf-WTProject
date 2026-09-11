<!DOCTYPE html>
<html >
<head>
    <title>Login - BookMyTurf</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="login-box">
        <h2>BookMyTurf Login</h2>
        <hr><br>

        <?php if(isset($_GET["successMsg"])): ?>
            <div class="success-msg">
                <?php $_GET["successMsg"]?>
            </div>
            <br>
        <?php endif; ?>

        <form action="../controllers/loginControls.php" method="post">
            
            <label for="email">Email Address:</label>
            <input type="text" name="email" id="email" placeholder="Enter your email">
            <span class="error">
                <?php 
                if(isset($_GET["emailErr"])) {
                    echo $_GET["emailErr"];
                }
                ?>
            </span>

            <label for="pass">Password:</label>
            <input type="password" name="pass" id="pass" placeholder="Enter your password">
            <span class="error">
                <?php 
                if(isset($_GET["passErr"])) {
                    echo $_GET["passErr"];
                }
                ?>
            </span>

            <input type="submit" name="submit" value="Login" id="submitBtn">
        </form>

        <?php if(isset($_GET["notFoundErr"])): ?>
            <div class="server-error error">
                <?php echo $_GET["notFoundErr"]; ?>
            </div>
        <?php endif; ?>

        <br>
        <div class="account-link-box">
            Don't have an account? <a href="register.php">Create Account</a>
        </div>

    </div>

</body>
</html>
