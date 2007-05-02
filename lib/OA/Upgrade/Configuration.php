<?php

require_once MAX_PATH . '/lib/max/Admin/Config.php';

class OA_Upgrade_Config
{

    var $oConfig;
    var $aConfig;

    function OA_Upgrade_Config()
    {
        $this->oConfig = new MAX_Admin_Config();
        $this->aConfig = &$this->oConfig->conf;
        if (!MAX_Admin_Config::isConfigWritable())
        {
            return false;
        }
    }

    /**
     * at installation time we need to derive some values
     *
     */
    function getInitialConfig()
    {
        $this->setValue('store','webDir', MAX_PATH . '/www/images');
        $this->guessWebpath();
    }

    function guessWebpath()
    {
        $path = dirname($_SERVER['PHP_SELF']);
        if (preg_match('#/www/admin$#', $path))
        {
            // User has web root configured as Max's root directory
            // so can guess at all locations
            $subpath = preg_replace('#/www/admin$#', '', $path);
            $basepath = $_SERVER['HTTP_HOST'] . $subpath. '/www/';
            $this->setValue('webpath', 'admin', $basepath.'admin');
            $this->setValue('webpath', 'delivery', $basepath.'delivery');
            $this->setValue('webpath', 'deliverySSL', $basepath.'delivery');
            $this->setValue('webpath', 'images', $basepath.'images');
            $this->setValue('webpath', 'imagesSSL', $basepath.'images');
        }
        else
        {
            // User has web root configured as Max's www/admin directory,
            // so can only guess the admin location
            $this->setValue('webpath', 'admin', $_SERVER['HTTP_HOST'] . '/');
        }
    }

    function writeConfig()
    {
        return $this->oConfig->writeConfigChange();
    }

    function setInstalledOn()
    {
        $this->setValue('max','installed', '1');
        return $this->writeConfig();
    }

    function setupConfigMax($aConfig)
    {
        $this->setValue('max', 'language', $aConfig['language']);
        //$this->setValue('max', 'requireSSL',    false);
    }

    function setupConfigTimezone($aConfig)
    {
        $this->setValue('timezone', 'location', $aConfig['location']);
    }

    function setupConfigWebpath($aConfig)
    {
        foreach ($aConfig AS $k => $v)
        {
            $this->setValue('webpath', $k, preg_replace('#/$#', '', $v));
        }
    }

    function setupConfigStore($aConfig)
    {
        $this->setValue('store', 'mode', 0);
        $this->setValue('store', 'webDir', $aConfig['webDir']);
    }

    function setupConfigDatabase($aConfig)
    {
        $this->setValue('database', 'type',     $aConfig['type']);
        $this->setValue('database', 'host',     $aConfig['host']);
        $this->setValue('database', 'port',     $aConfig['port']);
        $this->setValue('database', 'username', $aConfig['username']);
        $this->setValue('database', 'password', $aConfig['password']);
        $this->setValue('database', 'name',     $aConfig['name']);
    }

    function setupConfigTable($aConfig)
    {
        $this->setValue('table', 'prefix', $aConfig['prefix']);
        $this->setValue('table', 'type', $aConfig['type']);
//        $this->setValue('table', 'split', false);
//        $this->setGetValue('table', 'acls');
//        $this->setGetValue('table', 'acls_channel');
//        $this->setGetValue('table', 'ad_zone_assoc');
//        $this->setGetValue('table', 'ad_category_assoc');
//        $this->setGetValue('table', 'agency');
//        $this->setGetValue('table', 'application_variable');
//        $this->setGetValue('table', 'affiliates');
//        $this->setGetValue('table', 'banners');
//        $this->setGetValue('table', 'cache');
//        $this->setGetValue('table', 'campaigns');
//        $this->setGetValue('table', 'campaigns_trackers');
//        $this->setGetValue('table', 'category');
//        $this->setGetValue('table', 'channel');
//        $this->setGetValue('table', 'clients');
//        $this->setGetValue('table', 'data_intermediate_ad');
//        $this->setGetValue('table', 'data_intermediate_ad_connection');
//        $this->setGetValue('table', 'data_intermediate_ad_variable_value');
//        $this->setGetValue('table', 'data_raw_ad_click');
//        $this->setGetValue('table', 'data_raw_ad_impression');
//        $this->setGetValue('table', 'data_raw_ad_request');
//        $this->setGetValue('table', 'data_raw_tracker_click');
//        $this->setGetValue('table', 'data_raw_tracker_impression');
//        $this->setGetValue('table', 'data_raw_tracker_variable_value');
//        $this->setGetValue('table', 'data_summary_ad_hourly');
//        $this->setGetValue('table', 'data_summary_channel_daily');
//        $this->setGetValue('table', 'data_summary_zone_impression_history');
//        $this->setGetValue('table', 'images');
//        $this->setGetValue('table', 'log_maintenance_forecasting');
//        $this->setGetValue('table', 'log_maintenance_statistics');
//        $this->setGetValue('table', 'log_maintenance_priority');
//        $this->setGetValue('table', 'placement_zone_assoc');
//        $this->setGetValue('table', 'preference');
//        $this->setGetValue('table', 'session');
//        $this->setGetValue('table', 'targetstats');
//        $this->setGetValue('table', 'trackers');
//        $this->setGetValue('table', 'userlog');
//        $this->setGetValue('table', 'variables');
//        $this->setGetValue('table', 'zones');
    }

    function setValue($section, $name, $value)
    {
        $this->oConfig->setConfigChange($section, $name, $value);
    }

    function getValue($section, $name, $value)
    {
        return $this->oConfig->conf[$section][$name];
    }

    function setGetValue($section, $name)
    {
        $this->setValue($section, $name, $this->getValue($section, $name));
    }
}


?>