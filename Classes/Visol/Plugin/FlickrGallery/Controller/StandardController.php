<?php
namespace Visol\Plugin\FlickrGallery\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Visol.Plugin.FlickrGallery".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @return void
	 */
	public function indexAction() {
	
		$requiredSettings = array('apiKey', 'thumbnailSize', 'lightboxSize');
		foreach ($requiredSettings as $requiredSetting) {
			if (empty($this->settings[$requiredSetting])) {
				$this->addFlashMessage('Required setting "' . $requiredSetting . '" missing.');
			}
		}
		
		$currentNode = $this->request->getInternalArgument('__node');
		$photosetId = $currentNode->getProperty('album');
		$webserviceBase = 'http://api.flickr.com/services/rest/?format=json';
		$webserviceUrl = $webserviceBase . '&method=flickr.photosets.getPhotos&api_key=' . $this->settings['apiKey'] . '&photoset_id=' . $photosetId . '&nojsoncallback=1';
		$albumData = json_decode(file_get_contents($webserviceUrl));

		$this->view->assignMultiple(array(
			'node' => $currentNode,
			'albumData' => $albumData,
			'photosetId' => $photosetId,
			'title' => $currentNode->getProperty('title')
		));
	}

}

?>