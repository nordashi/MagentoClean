<?php
ini_set('display_errors','On');
ini_set('memory_limit','512M');
error_reporting(E_ALL);
require_once('abstract.php');

class Mage_Shell_Updater extends Mage_Shell_Abstract
{
    public function run()
    {
        $products = Mage::getResourceModel('catalog/product_collection');
        foreach($products as $product) {
            $attribute = $product->getResource()->getAttribute('media_gallery'); //get attribute
            $backend = $attribute->getBackend();
            $backend->afterLoad($product);
            
            $mediaGallery = $product->getMediaGallery();
            if (isset($mediaGallery['images']) ){
                echo "Setting {$product->getSku()} gallery. \n";
                foreach ($mediaGallery['images'] as $image){
                    Mage::getSingleton('catalog/product_action')
				            ->updateAttributes(array($product->getId()), array('image' => $image['file'], 'thumbnail' => $image['file'], 'small_image' => $image['file'] ), 0);
                    $this->count++;
                    break;
                }
            }
        }
        echo "{$this->count} product(s) updated.";
    }
}

$shell = new Mage_Shell_Updater();
$shell->run();
