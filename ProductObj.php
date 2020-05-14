<?php
/**
 * Store SQL data in custom object for easy access without accessing database again
 */
class ProductObj {
    public $ProdID;
    public $ProdName;
    public $ProdPrice;
    public $ProdImgUrl;

    function __construct($id_, $name_, $price_, $imgurl_) {
        $this->ProdID = $id_;
        $this->ProdName = $name_;
        $this->ProdPrice = $price_;
        $this->ProdImgUrl = $imgurl_;
    }
}