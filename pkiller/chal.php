<?php
 
$killers = array();
$admin = false;
class KillerJob {
    function __construct($isAdmin){
        $this->isAdmin = $isAdmin;
    }
    function __wakeup(){
        global $admin;
        $admin = $this->isAdmin;
    }
    function __toString() {
        global $killers;
        global $admin;
        if ($admin) {
            foreach ($killers as $tokill){
                echo shell_exec("kill " . $tokill)."<br>";
            }
        } else {
            echo "Admin not here, we can't kill yet!<br>";
        }
        return "";
    }
}
class addKill {
    function __construct($killme){
        global $killers;
        $killers[] = $killme;
    }
    function __wakeup(){
        global $killers;
        $killers[] = $this->killme;
    }
}
class MainClass {
    function __construct($name){
        $this->name = $name;
        echo "<p>Welcome ".$this->name."!<br></p>";
    }
    function __wakeup(){
        echo "Welcome back ".$this->name."!<br>";
    }
    function importProcesses($pids){
        $pids = unserialize($pids);
        foreach ($pids as $pid){
            if(filter_var($pid, FILTER_VALIDATE_INT) === false){
                die("Only integers!!!<br>");
            }
            new addKill($pid);
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Killer Control Panel</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2c3e50;
            color: #ecf0f1;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #e74c3c;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 1.8em;
            margin: 20px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #34495e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 1.1em;
            color: #ecf0f1;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: none;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 1em;
            background-color: #ecf0f1;
            color: #2c3e50;
            transition: background-color 0.3s;
        }
        input[type="text"]:focus {
            background-color: #bdc3c7;
            outline: none;
        }
        input[type="submit"] {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s, transform 0.2s;
        }
        input[type="submit"]:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }
        .form-section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Process Killer Control Panel</h1>
        
        <div class="form-section">
            <h2>Import Processes to Kill</h2>
            <form action="" method="post">
                <label for="pids">Enter Process IDs (as a PHP serialized object):</label>
                <input type="text" id="pids" name="pids" required>
                <input type="submit" value="Import Processes">
            </form>
        </div>

        <div class="form-section">
            <h2>Or Serialize Comma-Separated PIDs</h2>
            <form action="" method="post">
                <label for="commaPids">Enter Process IDs (comma-separated):</label>
                <input type="text" id="commaPids" name="commaPids" required>
                <input type="submit" value="Serialize PIDs">
            </form>
        </div>
    

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the first form was submitted
        if (isset($_POST['pids'])) {
            $pids = $_POST['pids'];
            $mainClass = new MainClass("User"); // You can change "User" to any name you want
            $mainClass->importProcesses(base64_decode($pids)); // Serialize the input for processing
        }
        // Check if the second form was submitted
        if (isset($_POST['commaPids'])) {
            $commaPids = $_POST['commaPids'];
            // Convert comma-separated PIDs into an array
            $pidArray = array_map('trim', explode(',', $commaPids));
            // Serialize the array into a PHP serialized object
            $serializedPids = serialize($pidArray);
            echo "<p>Serialized PIDs: <br>" . htmlspecialchars(base64_encode($serializedPids)) . "</p>";
        }
    }
    ?>
</div>
</body>
</html>
