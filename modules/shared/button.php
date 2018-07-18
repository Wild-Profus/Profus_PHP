<?php
class button{
    private $name;
    private $qty;

    public function __construct($item,$qty=1)    {
        $this->name = iconv("utf-8", "UTF-8//IGNORE", $item);
        $this->qty = $qty;
        $this->shop = "<i class='add_to_shop glyphicon glyphicon-plus btn btn-success' data-item='$item' data-qty='$qty'></i>";
    }

    public static function shopping($item,$qty=1){
        $item = str_replace("'", "&#39;",$item);
        return "<i class='add_to_shop glyphicon glyphicon-shopping-cart btn btn-success' data-item='".$item."' data-qty='$qty'></i>";
    }

    public static function remove(){
        return "<i class='remove glyphicon glyphicon-remove btn btn-danger'></i>";
    }
}