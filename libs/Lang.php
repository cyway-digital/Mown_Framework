<?php


	/**
	*	This object check the translations in the corrispective
	*	xml language file 
	*	@package Libs
	*/
	
	class Lang {
	
	/**
	*	Set the public propriety $userlan
	*	@access public
	*/
		
		public $userLang;
		
	/**
	*	Set the user language variable  from web browser
	*	@access public
	*/

		public function __construct(){
			//get language from browser
                    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '') {
                        $this->userLang = strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
                    } 
			
		}
		
	/**
	*	Translate from an id key
	*	@param string $phrase	the xml id key to check
	*	@param string $lang		the user lang that it's setcookie
	*	@param string $node		the xml node to parse
	*	@return string $value	the phrase translate
	*	@access public
	*/
		
            public function localize($phrase, $lang, $node=false) {
		// Static keyword is used to ensure the file is loaded only once
		static $translations = NULL;
		// If no instance of $translations has occured load the language file
		if (is_null($translations)) {
			$lang_file = LANGS_PATH . $lang . '.xml';
			if (!file_exists($lang_file)) {
				$lang_file = LANGS_PATH .'EN.xml';
				$lang = 'EN';
			}
				
			$xml = simplexml_load_file($lang_file);
			
			if ($node) {
			
				foreach ( $xml->$lang->$node->phrase as $value){
				
					if ($value['id'] == $phrase) {return $value;}
				
				}
				
			}	else {
			
					foreach ( $xml->$lang->phrase as $value){
					if ($value['id'] == $phrase) {return $value;}
				
				}

			}
		}
	}

	}

?>