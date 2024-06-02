<?php
/**
 * Provides everything needed for the Plugin
 */

   $config = file_get_contents(__DIR__.'/config.json');
   $config = json_decode($config, true);

   $this->loadTranslationsFrom(__DIR__.'/Lang', $config['configGroup'].'/'.$config['configKey']);
   $this->loadViewsFrom(__DIR__.'/Views', $config['configGroup'].'/'.$config['configKey']);
   $this->mergeConfigFrom(
      __DIR__.'/config.php', $config['configKey']
   );

   if(sc_config_global($config['configKey'])) {
      require_once __DIR__.'/function.php';
      //Path view admin
      view()->share('templatePath'.$config['configKey'], $config['configGroup'].'/'.$config['configKey'].'::');
      require_once __DIR__.'/Route.php';
   }