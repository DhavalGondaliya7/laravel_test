<?php
    function asset_url($path = ""){
        $full_path = "";
    
        if ($path != '') {
            $full_path = url('/public/'.$path);    
        }
        return $full_path;    
    }
?>
