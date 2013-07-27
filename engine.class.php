<?php
	
class Engine
{
   
	public $settings_file = "settings.ini";
	public $settings;
	public $files;

   function __construct() 
   {
     try
     {
     	
     	$this->getSettings();
     	$this->getEntries();
     	return $this;

     }catch(Exception $e)
     {
     	echo $e->getMessage();
     	die();
     }
   }

   private function getSettings()
   {
   		$this->settings = parse_ini_file($this->settings_file);
   		$this->checkSettings();
   }

   private function checkSettings()
   {
   		if($this->settings["items_per_page"] <= 0)
   		{
   			throw new Exception("items_per_page no esta definido", 1);   			
   		}

   		if($this->settings["folder_entries"] == "")
   		{
   			throw new Exception("folder_entries no esta definido", 1);   			
   		}   		
   }

   private function readDirectory()
   {
   		if(!$this->files = scandir($this->settings["folder_entries"]))
   		{
   			throw new Exception("No se pudo leer el directorio " . $this->settings["folder_entries"], 1);   			
   		}
   }

   public function getEntries()
   {

   		$this->readDirectory();
   }

   public function displaySettings()
   {
   		var_dump($this->settings);
   }
}