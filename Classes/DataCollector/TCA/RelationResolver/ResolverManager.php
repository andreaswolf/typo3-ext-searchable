<?php
namespace PAGEmachine\Searchable\DataCollector\TCA\RelationResolver;

use TYPO3\CMS\Core\SingletonInterface;

/*
 * This file is part of the PAGEmachine Searchable project.
 */

/**
 * 
 */
class ResolverManager implements SingletonInterface {

	protected $relationResolvers = [
		'select' => SelectRelationResolver::class
	];

	/**
	 * Returns a resolver for a given relation type, f.ex. 'select'
	 *
	 * @param  string $type 
	 * @return 
	 */
	public function getResolverForRelation($type) {

		if (empty($this->relationResolvers[$type])) {
			throw new \Exception('Relation type "' . $type . '" cannot be resolved.', 1487350002);
		}

		$classname = $this->relationResolvers[$type];
		return $classname::getInstance();

	}




}
