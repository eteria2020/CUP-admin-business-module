<?php

namespace CUPAdminBusinessModule;

use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;

class Module
{
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();

        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'onMergeConfig'));
    }

    public function onMergeConfig(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config = $configListener->getMergedConfig(false);
        if (isset($config['asset_manager']['resolver_configs']['collections']['js/trips.js'][0])) {
            unset($config['asset_manager']['resolver_configs']['collections']['js/trips.js'][0]);
        }
        // Pass the changed configuration back to the listener:
        $configListener->setMergedConfig($config);
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
