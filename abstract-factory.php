<?php
require('components.php');

abstract class AbstractFactory
{
    abstract public function createConnection(array $credentials): DBConnection;
    abstract public function createRecord(string $table, array $data): DBRecord;
    abstract public function createQueryBuiler(string $query): DBQueryBuiler;
}
 
class MySQLFactory extends AbstractFactory
{
    public function createConnection(array $credentials): DBConnection
    {
        return new MySQLConnection($credentials);
    }
 
    public function createRecord(string $table, array $data): DBRecord
    {
        return new MySQLRecord($data);
    }

    public function createQueryBuiler(string $query): DBQueryBuiler
    {
        return new MySQLQueryBuiler($query);
    }
}
 
class PostgreSQLFactory extends AbstractFactory
{
    public function createConnection(array $credentials): DBConnection
    {
        return new PostgreConnection($credentials);
    }
 
    public function createRecord(string $table, array $data): DBRecord
    {
        return new PostgreRecord($data);
    }

    public function createQueryBuiler(string $query): DBQueryBuiler
    {
        return new PostgreQueryBuiler($query);
    }
}

class OracleFactory extends AbstractFactory
{
    public function createConnection(array $credentials): DBConnection
    {
        return new OracleConnection($credentials);
    }
 
    public function createRecord(string $table, array $data): DBRecord
    {
        return new OracleRecord($data);
    }

    public function createQueryBuiler(string $query): DBQueryBuiler
    {
        return new OracleQueryBuiler($query);
    }
}


function clientCode(AbstractFactory $factory)
{
    $connect = $db->createConnection("localhost", "root", "root", "db");
    $record = $db->createRecord("test", [["id"=>"1", "name"=>"test1"], ["id"=>"2", "name"=>"test2"]]);
    $queryBuiler = $db->createQueryBuiler("SELECT * FROM `test`");
}

clientCode(new MySQLFactory());
clientCode(new PostgreSQLFactory());
clientCode(new OracleFactory());