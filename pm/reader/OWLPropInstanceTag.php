<?php
require_once "$OWLLIB_ROOT/reader/OWLTag.php";


/**
 *  Load information from <rdf:RDF> node
 *  All functions are implemented in OWLTag
 *
 *  @version	$Id: OWLPropInstanceTag.php,v 1.1 2004/03/29 07:27:50 klangner Exp $
 */
class OWLPropInstanceTag extends OWLTag
{
	
	//---------------------------------------------------------------------------
	/**
	 * create tag
	 */
	function create(&$model, $name, $attributes, $base)
  {
  	OWLTag::create($model, $name, $attributes, $base);

		$this->resources = array();
		
		if(array_key_exists($this->RDF_RESOURCE, $attributes)){
			$id = $this->addBaseToURI($attributes[$this->RDF_RESOURCE]);
			array_push($this->resources, $id);
		}
  }


	//---------------------------------------------------------------------------
	/**
	 * Get resources
	 */
	function getResources()
  {
  	return $this->resources;
  }

	
	//---------------------------------------------------------------------------
	/**
	 * process child:
	 *
	 */
	function processChild($child)
  {
 		$name = get_class($child);
  	if($name == "owlinstancetag"){
  		array_push($this->resources, $child->getID());
  	}
  }

	
	//---------------------------------------------------------------------------
	// Private members
	var	$resources;	
}

?>
