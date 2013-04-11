<?php
namespace Mongovel;

use MongoClient;

class DB
{
	/**
	 * Connection name (e.g., 'default')
	 * 
	 * @var string
	 */
	public $connection;
	
	/**
	 * DSN-like server string
	 *
	 * @var string
	 */
	public $server;
	
	/**
	 * DSN-like database name
	 * 
	 * @var string
	 */
	public $database;
	
	public function __construct()
	{
		$this->connection = 'default';
		
		$this->setDatabaseDSN();
	}
	
	public function setConnection($name)
	{
		$this->connection = $name;
		
		$this->setDatabaseDSN();
	}
	
	public function setDatabaseDSN($server = null, $database = null)
	{
		if (!is_null($server)) {
			$this->server   = $server;
			$this->database = $database;
		}
		else {
			// Fetch config data:
			
			$dsn = Mongovel::getContainer('config')->get('database.mongodb.' . $this->connection);
			
			$this->dsn      = $dsn;
			$this->server   = sprintf('mongodb://%s:%d', $dsn['host'], $dsn['port']);
			$this->database = $dsn['database'];
		}
	}
	
	public function getDatabaseDSN()
	{
		return $this->dsn;
	}
	
	/**
	 * Return a properly-configured MongoDB object
	 * 
	 * @return MongoDB
	 */
	public function db()
	{
		$m = new MongoClient($this->server);
		
		return $m->{$this->database};
	}
}
