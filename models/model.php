<?php
//function connects to the database
function dbConnexion(){

    $host = 'mysql-alicedev.alwaysdata.net';
    $dbName = 'alicedev_yogastudio';
    $login = 'alicedev';
    $password = 'jzF4R,24~';
    $connexion = False;

    if(!$connexion){

        try{
            $pdo = new PDO (
            'mysql:host='.$host.';dbname='.$dbName.';charset=utf8', 
            $login, 
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
            );
            return $pdo;
        }
        catch(PDOException $error){
            $code = $error->getCode();
            $message = $error->getMessage();
           // echo "Erreur de connexion: ".$error->getMessage();
           renderError($code, $message, 'layout');
        }
    }
}

/*
this function deletes row identified by given id from table also given in parameters 
@params int $id + string $table = indication where to delete
@returns bool 
*/
function delete(int $id, string $table): bool
{
        $pdo = dbConnexion();
        $query = $pdo->prepare(
            "DELETE FROM $table
            WHERE Id = :id");
    
        return $query->execute(['id' => $id]);
}


