<?php
require_once "$OWLLIB_ROOT/reader/OWLTag.php";


/**
 *  Load information from <rdf:RDF> node
 *  All functions are implemented in OWLTag
 *
 *  @version	$Id: OWLRangeTag.php,v 1.1 2004/03/29 07:27:50 klangner Exp $
 */
class OWLRangeTag extends OWLTag
{
	
	//---------------------------------------------------------------------------
	/**
	 * create tag
	 */
	function create(&$model, $name, $attributes, $base)
  {
  	OWLTag::create($model, $name, $attributes, $base);
  	
  	$this->resources = array();

		if(array_key_exists($this->RDF_RESOURCE, $attributes))
		{
			$id = $this->addBaseToURI($attributes[$this->RDF_RESOURCE]);
			//echo "OWLRangeTage.php: ".$id."<br>";
			array_push($this->resources, $id);
		}
		else {
		     //"http://www.w3.org/1999/02/22-rdf-syntax-ns#:resource"		
		    //echo "OWLRangeTage.php: not found : ";
		    //echo "(".count($attributes).") ";
		    //print_r(array_keys($attributes));
		    //echo "<br>";
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
  }

	
	//---------------------------------------------------------------------------
	// Private members
	var	$resources;	
}

?>
