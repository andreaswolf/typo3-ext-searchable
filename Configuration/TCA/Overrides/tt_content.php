<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['searchable_searchbar'] = 'select_key, pages, recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['searchable_livesearchbar'] = 'select_key, pages, recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['searchable_results'] = 'select_key, pages, recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['searchable_searchbar'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['searchable_livesearchbar'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['searchable_results'] = 'pi_flexform';
