<?php
namespace PAGEmachine\Searchable\Hook;

use PAGEmachine\Searchable\Query\DatabaseRecordUpdateQuery;
use TYPO3\CMS\Core\Database\PostProcessQueryHookInterface;

/*
 * This file is part of the PAGEmachine Searchable project.
 */

class DatabaseConnectionHook implements PostProcessQueryHookInterface
{
    /**
     * @var DatabaseRecordUpdateQuery
     */
    protected $updateQuery;

    /**
    * Post-processor for the SELECTquery method.
    *
    * @param string $select_fields Fields to be selected
    * @param string $from_table Table to select data from
    * @param string $where_clause Where clause
    * @param string $groupBy Group by statement
    * @param string $orderBy Order by statement
    * @param int $limit Database return limit
    * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    * @return void
    */
    public function exec_SELECTquery_postProcessAction(&$select_fields, &$from_table, &$where_clause, &$groupBy, &$orderBy, &$limit, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {
        // Nothing to do here
    }

    /**
    * Post-processor for the exec_INSERTquery method.
    *
    * @param string $table Database table name
    * @param array $fieldsValues Field values as key => value pairs
    * @param string|array $noQuoteFields List/array of keys NOT to quote
    * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    * @return void
    */
    public function exec_INSERTquery_postProcessAction(&$table, array &$fieldsValues, &$noQuoteFields, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {
        if (!is_array($GLOBALS['TCA'])) {
            return;
        }

        $this->getQuery()->updateToplevel($table, (int)$parentObject->sql_insert_id());

        //Special treatment for tt_content (since no connection to the pages record is triggered by the insert)
        if ($table == 'tt_content') {
            $this->getQuery()->updateToplevel('pages', (int)$fieldsValues['pid']);
        }
    }

    /**
    * Post-processor for the exec_INSERTmultipleRows method.
    *
    * @param string $table Database table name
    * @param array $fields Field names
    * @param array $rows Table rows
    * @param string|array $noQuoteFields List/array of keys NOT to quote
    * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    * @return void
    */
    public function exec_INSERTmultipleRows_postProcessAction(&$table, array &$fields, array &$rows, &$noQuoteFields, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {
        // Nothing to do here
    }

    /**
    * Post-processor for the exec_UPDATEquery method.
    *
    * @param string $table Database table name
    * @param string $where WHERE clause
    * @param array $fieldsValues Field values as key => value pairs
    * @param string|array $noQuoteFields List/array of keys NOT to quote
    * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    * @return void
    */
    public function exec_UPDATEquery_postProcessAction(&$table, &$where, array &$fieldsValues, &$noQuoteFields, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {
        if (!is_array($GLOBALS['TCA'])) {
            return;
        }

        $uid = $this->extractUidFromWhereClause($where);

        if ($uid !== null) {
            $this->getQuery()->updateToplevel($table, (int)$uid);
            $this->getQuery()->updateSublevel($table, (int)$uid);
        }
    }

    /**
    * Post-processor for the exec_DELETEquery method.
    *
    * @param string $table Database table name
    * @param string $where WHERE clause
    * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    * @return void
    */
    public function exec_DELETEquery_postProcessAction(&$table, &$where, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {
        if (!is_array($GLOBALS['TCA'])) {
            return;
        }

        $uid = $this->extractUidFromWhereClause($where);

        if ($uid !== null) {
            $this->getQuery()->updateToplevel($table, (int)$uid);
            $this->getQuery()->updateSublevel($table, (int)$uid);
        }
    }

    /**
    * Post-processor for the exec_TRUNCATEquery method.
    *
    * @param string $table Database table name
    * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    * @return void
    */
    public function exec_TRUNCATEquery_postProcessAction(&$table, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {
        // Nothing to do here
    }

    /**
     * Extract UID from a where clause
     *
     * @param string $where the where clause
     * @return int|null
     */
    protected function extractUidFromWhereClause($where)
    {
        $uid = null;
        $matches = [];
        $count = preg_match('/^uid\s?=\s?(?<uid>[0-9]*)$/', $where, $matches);

        if ($count === 1) {
            $uid = (int)$matches['uid'];
        }

        return $uid;
    }

    /**
     * @return DatabaseRecordUpdateQuery
     */
    protected function getQuery()
    {
        if ($this->updateQuery == null) {
            $this->updateQuery = new DatabaseRecordUpdateQuery();
        }

        return $this->updateQuery;
    }
}
