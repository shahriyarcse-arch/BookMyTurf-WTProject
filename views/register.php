<!DOCTYPE html>
<html >
<head>
    <title>Register - BookMyTurf</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="login-box">
        <h2>Create an Account</h2>
        <hr><br>

        <form action="../controllers/registerControls.php" method="post">
            
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter your full name" value="">
            <span class="error">
                <?php 
                if(isset($_GET["nameErr"])) {
                    echo $_GET["nameErr"];
                }
                ?>
            </span>

            <label for="email">Email Address:</label>
            <input type="text" name="email" id="email" placeholder="e.g. name@example.com" value="">
            <span class="error">
                <?php 
                if(isset($_GET["emailErr"])) {
                    echo $_GET["emailErr"];
                }
                ?>
            </span>
            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" placeholder="e.g. 01712345678" value="">
            <span class="error">
                <?php 
                if(isset($_GET["phoneErr"])) {
                    echo $_GET["phoneErr"];
                }
                ?>
            </span>

            <label for="role">Register As:</label>
            <select name="role" id="role" class="select-role">
                <option value="customer">Customer (Book Turfs)</option>
                <option value="owner">Turf Owner (Manage Turfs)</option>
            </select>

            <label for="pass">Password:</label>
            <input type="password" name="pass" id="pass" placeholder="Create a password">
            <span class="error">
                <?php 
                if(isset($_GET["passErr"])) {
                    echo $_GET["passErr"];
                }
                ?>
            </span>

            <label for="confirmPass">Confirm Password:</label>
            <input type="password" name="confirmPass" id="confirmPass" placeholder="Repeat your password">
            <span class="error">
                <?php 
                if(isset($_GET["confirmPassErr"])) {
                    echo $_GET["confirmPassErr"];
                }
                ?>
            </span>

            <input type="submit" name="submit" value="Create Account" id="submitBtn">
        </form>

        <span class="error server-error">
            <?php 
            if(isset($_GET["existErr"])) {
                echo $_GET["existErr"];
            }
            if(isset($_GET["serverErr"])) {
                echo $_GET["serverErr"];
            }
            ?>
        </span>

        <br>
        <div class="account-link-box">
            Already have an account? <a href="login.php">Login here</a>
        </div>

    </div>

</body>
</html>
