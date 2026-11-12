<?php
//Database connection
/*
   To test connection, please go to:
   http://localhost/main-store/settings/database.php
*/

    //Local connection
    $local_host = "localhost";
    $local_port = "5432";
    $local_dbname = "mainstore";
    $local_user = "postgres";
    $local_password = "play3001";
 
    $local_conn = pg_connect(
        "
        host=$local_host
        port=$local_port
        dbname=$local_dbname
        user=$local_user
        password=$local_password
    "
    );

    //Supa connection
    $supa_host = "aws-1-us-west-2.pooler.supabase.com";
    $supa_port = "6543";
    $supa_dbname = "postgres";
    $supa_user = "postgres.quqawpmlsnoununphesg";
    $supa_password = "main-store@@";
 
    $supa_conn = pg_connect(
        "
        host=$supa_host
        port=$supa_port
        dbname=$supa_dbname
        user=$supa_user
        password=$supa_password
    "
    );
 
    if (!$local_conn) {
        die("Error local connection");
    }else{
        echo "Success database local connection !!!";
    }

     if (!$supa_conn) {
        die("Error supa connection");
    }else{
        echo "<br>Success database supa connection !!!";
    }



?>