<?php

/**
 * Burlesque Database Interface.
 * Handle all database interactions from creating to querying.
 */

/**
 * Users
 *
 * Add User
 * Update User
 *  New Login
 *  New Post
 *  Logout
 *  Name Change
 *  Forum Name Change
 *  Update Aspects
 *  Kick
 * Get Users in Room
 * Get User Details
 */

/**
 * Posts
 *
 * Add Post
 * Get Posts In Room For User
 * Get All Posts In Room
 * Delete Post
 */

/**
 * Connect to Burlesque database and perform all needed operations.
 * This class handles all database transactions for Burlesque, including
 * table creation, adds, updates, deletes, and selects.
 */
class Burlesque_DB_Connection
{
    public $table_prefix;    
    private $pdo;
    
    public $error = false;
    
    /**
     * @param string $username the name to use when connecting to the database
     * @param string $password the password to connect to the chat database
     * @param string $database the name of the database to use (default: burlesque)
     * @param string $table_prefix a prefix to apply to each table's name (default: none)
     * @param string $host the hostname or IP of the database server (default: localhost)
     * @param string $post the port number to use to connect to mysql (default: 3306)
     *
     * @return boolean
     */
    public function __construct($username, $password, $database="burlesque",
                                $table_prefix = "", $host="localhost",
                                $port = "3306")
    {
        $this->table_prefix = $table_prefix;
        try
        {
            $this->pdo = new PDO(
                                 'mysql:host='.$host.';port='.$port.';dbname='.$database,
                                 $username,
                                 $password);
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        }
        catch(PDOException $e)
        {
            $this->error += $e->getMessage();
            return false;
        }
        return true;
    }
    
    /**
     * Convert SQL query to PDOStatement
     *
     * @param string $query the SQL query to prepare
     *
     * @return PDOStatement a PDO prepared statement for execution
     */
    public function prepare($query)
    {
        return $this->pdo->prepare($query);
    }
    
    /**
     * Attempt to exectute a query, capture any errors to an internal log.
     *
     * @param PDOStatement $query the query to execute (must be a PDO prepared statement)
     * @param string $label A label for this query used in any error reporting
     * @param array|boolean $bind A key/value array of additional parameters to bind to the query
     */
    public function execute($query, $label, $bind = false)
    {
        if($bind)
            $result = $query->execute($bind);
        else
            $result = $query->execute();
        if(!$result)
        {
            $this->error += "\n\n Problem with" . $label . " Error contents: ";
            $this->error += print_r($query->getErrorInfo(), true);
        }
    }
    
    /**
     * Get the most recent insert ID from PDO
     * @return integer
     */
    public function last_insert_id()
    {
        return $this->pdo->lastInsertId();
    }
}
?>