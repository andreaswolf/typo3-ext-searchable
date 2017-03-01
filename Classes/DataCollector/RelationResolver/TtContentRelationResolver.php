<?php
namespace PAGEmachine\Searchable\DataCollector\RelationResolver;

use PAGEmachine\Searchable\DataCollector\DataCollectorInterface;
use PAGEmachine\Searchable\DataCollector\RelationResolver\RelationResolverInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/*
 * This file is part of the PAGEmachine Searchable project.
 */

/**
 * 
 */
class TtContentRelationResolver implements SingletonInterface, RelationResolverInterface {

    /**
     *
     * @var \TYPO3\CMS\Frontend\Page\PageRepository
     */
    protected $pageRepository;

    /**
     *
     * @return TtContentRelationResolver
     */
    public static function getInstance() {

        return GeneralUtility::makeInstance(self::class);
    }

    /**
     * @param PageRepository|null $pageRepository
     */
    public function __construct(PageRepository $pageRepository = null) {

        $this->pageRepository = $pageRepository ?: GeneralUtility::makeInstance(PageRepository::class);

    }

    /**
     * Resolves a relation between pages and content
     *
     * @param  string $fieldname
     * @param  array $record The record containing the field to resolve
     * @param  DataCollectorInterface $childCollector
     * @param  DataCollectorInterface $parentCollector
     * @return array $processedField
     */
    public function resolveRelation($fieldname, $record, DataCollectorInterface $childCollector, DataCollectorInterface $parentCollector) {

        $processedField = [];

        $contentUids = $this->fetchContentUids($record['uid']);
        foreach ($contentUids as $content) {

            $processedField[] = $childCollector->getRecord($content['uid']);
        }

        return $processedField;




    }

    /**
     * Fetches content uids to transfer to datacollector
     * 
     * @param  int $pid
     * @return array
     */
    protected function fetchContentUids($pid) {

        $content = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid', 'tt_content', 'pid = ' . $pid . $this->pageRepository->enableFields('tt_content') . BackendUtility::deleteClause('tt_content'));

        return $content;


    }



}