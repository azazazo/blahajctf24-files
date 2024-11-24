<?php 
    function makeDb($name){
        if (!preg_match('/^[0-9]+$/', $name) || strlen($name) != 6) {
            echo '<p>No hacking!!! This is not a LFI challenge dammit</p>';
            throw new InvalidArgumentException('Invalid database name');
        }
        
        $db = new SQLite3('databases/'.$name.'.db');

        $db->exec("CREATE TABLE IF NOT EXISTS users (
            username TEXT UNIQUE,
            userObject TEXT
        )");
        return $db;
    }

    class User{
        protected $_username;
        protected $_password;
        protected $_admin;
        protected $_reserved;
        
        public function __construct($username, $password){
            $this->_username = $username;
            $this->_password = $password;
            $this->_admin = false;
            $this->_reserved = "";
        }
        
        public function getName(){
            return $this->_username;
        }
        
        public function checkpass($pass){
            return $this->_password == $pass;
        }
        
        public function isAdmin(){
            return $this->_admin;
        }
    }

    function insertUser($db, $userObject) {
        $userser = str_replace(chr(0) . '*' . chr(0), '\0\0\0', serialize($userObject)); //database does not support null bytes, and the protected field has a \x00*\x00 preface. very bad
        $stmt = $db->prepare("INSERT INTO users (username, userObject) VALUES (:username, :userObject)");
        $stmt->bindValue(':username', $userObject->getName(), SQLITE3_TEXT);
        $stmt->bindValue(':userObject', $userser, SQLITE3_TEXT);
        $stmt->execute();
    }

    function getUserObject($db, $username, $pass){
        $stmt = $db->prepare("SELECT userObject FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $user = unserialize(str_replace('\0\0\0', chr(0) . '*' . chr(0), $row['userObject']));
            if($user->checkpass($pass)){
                return $user;
            }
            return null;
        } else {
            return null;
        }
    }

    // Handle database creation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_db'])) {
        $dbname = random_int(100000, 999999); // Generate a random database name
        makeDb($dbname);
        echo "<p>Database '$dbname' created successfully.</p>";
    }
    // Handle account creation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['dbname'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $dbname = $_POST['dbname'];
        $db = makeDb($dbname);
        
        $user = new User($username, $password);
        insertUser($db, $user);
        echo "<p>Account created successfully for user '$username'.</p>";
    }

    // Handle login
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_username']) && isset($_POST['login_password']) && isset($_POST['dbname'])) {
        $username = $_POST['login_username'];
        $password = $_POST['login_password'];
        
        $dbname = $_POST['dbname'];
        $db = makeDb($dbname);
        
        $user = getUserObject($db, $username, $password);
        if ($user) {
            echo "<p>Login successful for user '$username'. </p>";
            if ($user->isAdmin()) {
                echo "<p>Wow, you are an admin! Here is your flag: [FLAG REDACTED]</p>";
            } else {
                echo "<p>But you are not an admin!</p>";
            }
        } else {
            echo "<p>Invalid username or password.</p>";
        }
    }
?>