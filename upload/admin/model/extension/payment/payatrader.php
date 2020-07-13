<?php
class ModelExtensionPaymentPayatrader extends Model {

	protected $_searchTitle = 'Acceptacard Modification for Paya Card Services';
	
	public function install($version = '1.0.0') {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "modification` WHERE `name` LIKE '%".$this->_searchTitle."%' AND `version` = '".$version."'");
		if(!$query->row) {
			$file = DIR_DOWNLOAD .'payatrader.xml';
			$this->load->model('extension/modification');

			// If xml file just put it straight into the DB
			$xml = file_get_contents($file);

			if ($xml) {
				try {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($xml);

					$name = $dom->getElementsByTagName('name')->item(0);

					if ($name) {
						$name = $name->nodeValue;
					} else {
						$name = $dom->getElementsByTagName('id')->item(0);
						if ($name) {
							$name = $name->nodeValue;
						} else {
							$name = '';
						}
					}

					$author = $dom->getElementsByTagName('author')->item(0);

					if ($author) {
						$author = $author->nodeValue;
					} else {
						$author = '';
					}

					$version = $dom->getElementsByTagName('version')->item(0);

					if ($version) {
						$version = $version->nodeValue;
					} else {
						$version = '';
					}

					$link = $dom->getElementsByTagName('link')->item(0);

					if ($link) {
						$link = $link->nodeValue;
					} else {
						$link = '';
					}

					$modification_data = array(
						'name'       => $this->_searchTitle,
						'author'     => $author,
						'version'    => $version,
						'link'       => $link,
						'code'       => $xml,
						'status'     => 1
					);

					$this->model_extension_modification->addModification($modification_data);
					return true;
				} catch(Exception $exception) {
					return false;
				}
			}
		}
		return true;
	}

	public function uninstall($version = '1.0.0') {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "modification` WHERE `name` LIKE '%".$this->_searchTitle."%' AND `version` = '".$version."'");
	}
}