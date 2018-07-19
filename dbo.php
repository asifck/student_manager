<?php


function connectDb() {
    require_once 'config.php';
    try {
        $pdo = new PDO("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage(); exit;
    }

    return $pdo;
}



function getAll($tableName, $filters = array(), $page = null)
{

    $pdo = connectDb();
    if(!is_null($page)) {
        //0-9, 10-19
        $limit = 10;
        //starting record index - (1*10)-10 = 0, (2*10)-10 = 10
        $offset = ($page*$limit)-$limit;

        // Add query parts as LIMIT and OFFSET to paginate

        $paginationQueryPart = ' LIMIT '.$limit. ' OFFSET '.$offset;
    }
    else {
        // if $page is null we have to fetch all records.
        $paginationQueryPart = '';
    }


    //prepare the filter query parts (WHERE condition)

    if(!empty($filters)) {
        $addWhere = addWhere($filters);
    }
    else {
        $addWhere = '';
    }

    $query = 'SELECT * FROM `'.$tableName.'`'.$addWhere.$paginationQueryPart;
    echo $query."<br>";
    $result = $pdo->query($query);
    $records = $result->fetchAll();
    return $records;
}

function addWhere($filters) {
    $wherePart = ' WHERE ';
    $i = 0;
    foreach($filters as $key => $filter) {
        if(!empty($filter)) {
            $wherePart = $wherePart . $key . ' = ' . $filter . ' ';
            if (count($filters) > 1 && $i < (count($filters) - 1)) {
                $wherePart = $wherePart . ' AND ';
            }
        }
        $i++;
    }

    return $wherePart;
}

function getOne($tableName, $id) {
    $pdo = connectDb();
    $result = $pdo->query('SELECT * FROM `'.$tableName.'` WHERE id = '.$id);
    $record = $result->fetch();
    return $record;
}



function getColumns($values) {
    $columns = array_keys($values);

    $columnString = '';

    foreach($columns as $key => $column) {
        if($key < (count($columns)-1)) {
            $comma = ', ';
        }
        else {
            $comma = '';
        }
        $columnString = $columnString.'`'.$column.'`'.$comma;
        //mansilakkan = ''.`name`,.
    }

    return $columnString;
}

function getParameters($values) {
    $columns = array_keys($values);

    $paramString = '';

    foreach($columns as $key => $column) {
        if($key < (count($columns)-1)) {
            $comma = ', ';
        }
        else {
            $comma = '';
        }
        $paramString = $paramString.':'.$column.$comma;
    }

    return $paramString;
}

function createRecord($tableName, $values) {
    $pdo = connectDb();
    $columnsString = getColumns($values);
    $paramString = getParameters($values);
    $statement = $pdo->prepare("INSERT INTO `".$tableName."` (".$columnsString.") values (".$paramString.")");
    $result = $statement->execute($values);

    return $result;
}

function getUpdateQueryParts($values) {
    $columns = array_keys($values);
    $updateQueryParts = '';
    foreach($columns as $key => $column) {
        if($key < (count($columns)-1)) {
            $comma = ', ';
        }
        else {
            $comma = '';
        }
        $updateQueryParts = $updateQueryParts.'`'.$column.'` = :'.$column.$comma;
    }

    return $updateQueryParts;
}

function updateRecord($tableName, $id, $values) {
     $pdo = connectDb();
     $updateQueryParts = getUpdateQueryParts($values);
     $statement = $pdo->prepare("UPDATE `".$tableName."` SET ".$updateQueryParts." WHERE `id` = ".$id);
     $result = $statement->execute($values);

     return $result;
}

function deleteRecord($tableName, $id) {
    $pdo = connectDb();
    $statement = $pdo->prepare(" DELETE FROM `".$tableName."` WHERE `id` = ".$id);
    $result = $statement->execute();

    return $result;
}


//function getReportData($month, $year, $account) {
//    $pdo = connectDb();
//    $query = 'SELECT * FROM `expenses` WHERE MONTH(`transaction_date`) = '.$month.
//        ' AND YEAR(`transaction_date`) = '.$year;
//    if(!is_null($account)) {
//        $query = $query.' AND `account_id` = '.$account;
//    }
//
//    $result = $pdo->query($query);
//    $records = $result->fetchAll();
//    return $records;
//}