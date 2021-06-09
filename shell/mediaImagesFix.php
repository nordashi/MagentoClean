<?php 
// Original from: https://stackoverflow.com/questions/19153891/magento-how-to-programatically-set-the-base-image-to-the-first-image-in-the-lis
ini_set('display_errors','On');
ini_set('memory_limit','512M');
error_reporting(E_ALL);
require_once('abstract.php');

class Mage_Shell_Updater extends Mage_Shell_Abstract
{
    public function run()
    {
        $products = Mage::getResourceModel('catalog/product_collection');
        foreach($products as $p) {
            $pid = $p->getId();
            $product = Mage::getModel('catalog/product')->load($pid);
            $mediaGallery = $product->getMediaGallery();
            if (isset($mediaGallery['images'])){
                foreach ($mediaGallery['images'] as $image){
                    Mage::getSingleton('catalog/product_action')
                    ->updateAttributes(array($pid), array('image'=>$image['file']), 0);
                    $this->count++;
                    break;
                }
            }
        }
        echo("{$this->count} product(s) updated.");
    }
}

$shell = new Mage_Shell_Updater();
$shell->run();
