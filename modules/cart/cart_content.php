<?php
ini_set('session.cookie_lifetime', 86400 * 7);
session_set_cookie_params(86400 * 7);
session_start();
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
if (isset($_SESSION['cart'])){
    $size = count($_SESSION['cart']);
    $btn = button::remove();
    $i = 0;
    $j = 0;
    if ($size>0){
        foreach ($_SESSION['cart'] as $item=>$array){
            if (isset($_SESSION['cart'][$item]['quantity']['self'])){
                $global[$i]['name']=$array['info']['name'];
                $global[$i]['image']='<img class="img-item" src="/images/all/'.$array['info']["iconId"].'.png" alt="'.$array['info']["name"].'" title="'.$array['info']["name"].'" style="width:60px;">';
                $global[$i]['from'] = ucfirst($_SESSION['cart'][$item]['info']['from']);
                $global[$i]['level'] = $_SESSION['cart'][$item]['info']['level'];
                if(isset($_SESSION['cart'][$item]['info']['recipe'])){
                    $global[$i]['metier'] =$_SESSION['cart'][$item]['info']['metier'];
                    $global[$i]['recipe'] =$_SESSION['cart'][$item]['info']['recipe'];
                }else{
                    $global[$i]['metier'] = "Non craftable";
                    $global[$i]['recipe'] = "";
                }
                $qty = $_SESSION['cart'][$item]['quantity']['self'];
                $global[$i]['quantity'] ="
                    <i class='btn btn-sm btn-default glyphicon glyphicon-minus' style='margin-bottom:2px;'></i>
                    <input class='form-control cart-qty' value='$qty' style='text-align:right;width:50px;'>
                    <i class='btn btn-sm btn-default glyphicon glyphicon-plus' style='margin-bottom:2px;'></i>";
                $global[$i]['btn']=$btn;
                $i++;
            }
            if(!isset($_SESSION['cart'][$item]['info']['recipe'])){
                $detailed[$j]['name'] = $array['info']['name'];
                $detailed[$j]['image']='<img class="img-item" src="/images/all/'.$array['info']["iconId"].'.png" alt="'.$array['info']["name"].'" title="'.$array['info']["name"].'" style="width:60px;">';
                $temp = $_SESSION['cart'][$item]['quantity'];
                $for = "";
                $qty = 0;
                foreach ($temp as $craft=>$craft_qty_for_one){
                    if ($craft==='self'){
                        $qty = $qty + intval($craft_qty_for_one);
                    }else{
                        $qty = $qty + intval($_SESSION['cart'][$craft]['quantity']['self'])*intval($craft_qty_for_one);
                    }
                    $for = $for ."[".$_SESSION['cart'][$craft]['info']['name']."]";
                };
                $detailed[$j]['hdv'] = $_SESSION['cart'][$item]['info']['hdv'];
                $detailed[$j]['qty'] = $qty;
                $detailed[$j]['for'] = $for;
                $j++;
            }
        }
        $results=[0=>$global,1=>$detailed];
        json($results);
    }
}