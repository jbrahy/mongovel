<?php
namespace Mongovel;

use Illuminate\Support\Str;
use MongoId;

/**
 * The base class implementing Eloquent-ier methods
 */
class Mongovel
{

	////////////////////////////////////////////////////////////////////
	////////////////////////////// METHODS /////////////////////////////
	////////////////////////////////////////////////////////////////////

	/**
	 * Returns an instance of the model populated with data from Mongo
	 *
	 * @param array $parameters
	 *
	 * @return Model
	 */
	public static function findOne($parameters)
	{
		$parameters = static::handleParameters($parameters);

		$results = static::getCollection()->findOne($parameters);

		return static::getModelInstance($results);
	}

	/**
	 * Allows the passing of a string or a MongoId as query
	 *
	 * @param mixed $query
	 * @param array $update
	 */
	public static function update($query, $update)
	{
		$query = static::handleParameters($query);

		return static::getCollection()->update($query, $update);
	}

	////////////////////////////////////////////////////////////////////
	////////////////////////////// HELPERS /////////////////////////////
	////////////////////////////////////////////////////////////////////

	/**
	 * Get the collection name of the model
	 *
	 * @return string
	 */
	protected static function getCollectionName()
	{
		$collectionName = Str::plural(get_called_class());
		static::$collectionName = strtolower($collectionName);

		return static::$collectionName;
	}

	/**
	 * Magically handles MongoIds when passed as strings or objects
	 *
	 * @param string|array|MongoId $parameters An array of parameters or a MongoId (string/object)
	 *
	 * @return array
	 */
	protected static function handleParameters($parameters)
	{
		// Assume it's a MongoId
		if (is_string($parameters)) {
			return array('_id' => new MongoId($parameters));
		} elseif ($parameters instanceof MongoId) {
			return array('_id' => $parameters);
		}

		return $parameters;
	}
}
