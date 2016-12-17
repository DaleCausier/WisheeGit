<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE.'/components/com_wishee/vendor/autoload.php';

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;
/**
 * Wishee Search Model
 *
 * @since  0.0.1
 */
class WisheeModelSearch extends JModelForm {
    
    protected $results;
    
    public function getForm($data = array(), $loadData = true) {
        // Get the Search form
        $form = $this->loadForm('com_wishee.search', 'search', array('load_data' => $loadData));
        
        if (empty($form)) {
            return false;
        }
        
        return $form;
    }
    
    public function getProducts($formData) {
        
        $client = new \GuzzleHttp\Client();
        $request = new \ApaiIO\Request\GuzzleRequest($client);
        
        $conf = new GenericConfiguration();
        $conf
            ->setCountry('co.uk')
            ->setAccessKey('AKIAI4Q3F4IO4CJZGDBQ')
            ->setSecretKey('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')
            ->setAssociateTag('wishee0a-21')
            ->setRequest($request);
        
        $search = new Search();
        $search->setCategory($formData['category']);
        #$search->setKeywords('The Lord of the Rings');
        $search->setKeywords($formData['search_query']);
        $search->setResponsegroup(array('Medium', 'Images'));
        
        $apaiIO = new ApaiIO($conf);
        $response = $apaiIO->runOperation($search);
        
        $allResults = simplexml_load_string($response);
        $this->results = $allResults->Items;
        
        return $this->results;
        
    }
    
}