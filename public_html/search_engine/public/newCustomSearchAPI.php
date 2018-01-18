<?php

/**
* @author Satya
* @description - It gives the result in easy to use PHP array form. Calling methods are down the code. 
*             Commented codes at last are left to show how the code is working. 
*             It allow you to choose the return method by Google - JSON or XML. In both case it will return                      *             easy to use PHP array.
*             
* @return  - PHP Array having Title, url and desc/snippets
*/

require './util.php';

interface SearchFormatStrategy {

	function getData(Google_Search $search);
}

class JSONStrategy implements SearchFormatStrategy {

	// Request JSON formatted search response
	public	function  getData(Google_Search $searchRes) {

		$params = array('key' => $searchRes::API_KEY,
						'cx' => $searchRes::CX,
						'q' => $searchRes->getQuery(),
						'alt'=>'json'
						);

		$url = $searchRes::BASE_URL . '?' . http_build_query($params, '', '&');

		$response = getSearchResult($url);

		//print_r($response);

		if ($response['errno'] == 0) {

			$responseArr =  json_decode($response['content']);

		}
		else {

			echo 'Error! Trouble somewehre. <br>' . $response['errmsg'];
		}

		foreach ($responseArr->items as $item) {

			// $item->htmlTitle, $item->snippet
			$outputArr[] =  array($item->title, $item->link, $item->htmlSnippet);
		}

		return $outputArr;

	}
}

class ATOMStrategy implements SearchFormatStrategy {

	// Request ATOM formatted search response
	public	function  getData(Google_Search $searchRes) {

		$params = array('key' => $searchRes::API_KEY,
						'cx' => $searchRes::CX,
						'q' => $searchRes->getQuery(),
						'alt'=>'atom'
						);

		$url = $searchRes::BASE_URL . '?' . http_build_query($params, '', '&');

		$response = getSearchResult($url);

		if ($response['errno'] == 0) { // no error

			return $this->_blogFeed($response['content']);

		}
		else {

			echo 'Error! Trouble somewehre. <br>' . $response['errmsg'];
		}

	}

    private function _blogFeed($rssXML)
    {
	libxml_use_internal_errors(true);

        $doc = simplexml_load_string($rssXML);
		$xml = explode("\n", $rssXML);

		if (!$doc) {
			$errors = libxml_get_errors();

			foreach ($errors as $error) {
				echo $this->display_xml_error($error, $xml);
			}

			libxml_clear_errors();
		}

        if(count($doc) == 0) return;

		$docArr = json_decode(json_encode($doc),true);
		$entries = $docArr['entry'];

        foreach($entries as $item)
        {

		   $this->title		=	$item['title'];
		   $this->link		=	$item['link']['@attributes']['href'];
		   $this->summary	=	$item['summary'];

            $post = array(
				'title'=>		$this->title,
				'link' =>		$this->link,
				'summary' =>	$this->summary

			);

            $this->posts[] = $post;

        }

		return $this->posts;

    }

       // This I copied from somewhere
	public function display_xml_error($error, $xml)
	{
		$return  = $xml[$error->line - 1] . "\n";
		$return .= str_repeat('-', $error->column) . "^\n";

		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				$return .= "Warning $error->code: ";
				break;
			 case LIBXML_ERR_ERROR:
				$return .= "Error $error->code: ";
				break;
			case LIBXML_ERR_FATAL:
				$return .= "Fatal Error $error->code: ";
				break;
		}

		$return .= trim($error->message) .
				   "\n  Line: $error->line" .
				   "\n  Column: $error->column";

		if ($error->file) {
			$return .= "\n  File: $error->file";
		}

		return "$return\n\n--------------------------------------------\n\n";
	}

}

class Google_Search {

	CONST BASE_URL	= 'https://www.googleapis.com/customsearch/v1';
	CONST API_KEY	= 'your api key';
	CONST CX		= 'custom search engine key';

	private $query = ''; 

	public function __construct ($queryTerm_) {

		$this->setQuery($queryTerm_);

	}

	public function getData(SearchFormatStrategy $strategyObject) {

		return $strategyObject->getData($this);

	}

	public function setQuery ($queryTerm_) {

		$this->query = $queryTerm_;

	}

	public function getQuery () {

		return $this->query;

	}

}

$google = new Google_Search('Bihar Election');

echo '<pre>';
// each array value should be encoded in production env.
print_r($google->getData(new JSONStrategy));

echo '<hr>';

//print_r($google->getData(new ATOMStrategy));
//$google->setQuery('PHP');
//print_r($google->getData(new ATOMStrategy));

?>
