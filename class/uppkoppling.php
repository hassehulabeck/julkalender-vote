<?php

/**
 * uppkoppling
 * 
 * 
 * @author  unknown unknown@no-idea.net>
 */

class uppkoppling {

    public function conn() {

        include 'db.php';  

        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass, $options);
            #Set Error Mode to ERRMODE_EXCEPTION.
            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
        }
        catch(PDOException $e) {  
            echo "Oops. Ingen kontakt med servern.<br />";  
            file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);  
        }  
        
        return $pdo;
    }

}

?>
