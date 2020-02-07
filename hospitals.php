<?php

$db_connection = pg_connect("host=127.0.0.1 port=5433 dbname=road_tweets user=postgres password=root options='--client_encoding=UTF8'")
or die('connection failed');
$result = pg_query($db_connection, "SELECT name, ST_X(geom) as lat, ST_Y(geom) as long FROM hospitals");
echo json_encode(['success' => true, 'hospitals' => pg_fetch_all($result)]);

pg_close($db_connection);