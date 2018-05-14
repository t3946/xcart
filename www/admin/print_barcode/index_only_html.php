<?php
    ini_set("display_errors",1);
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Print Barcode</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/css.css" rel="stylesheet">
    </head>
    <body>
        <table>
            <tr>
                <?php
                    $file=fopen('barcode.txt','r');
                    $i=1;
                    while(!feof($file)){
                        $str=fgets($file);
                        $fields=explode(';',$str);
                        for($j=0;$j<$fields[2];$j++){
                            echo '<td class="cell">';
                            echo '<img src="http://www.scandit.com/wp-content/themes/bridge-child/wbq_barcode_gen.php?symbology=ean13&value='.$fields[1].'&ec=L&size=100" />';
                            echo '<br />'.$fields[0];
                            echo '</td>';
                            if($i%3==0){
                                echo '</tr><tr>';
                            }else{
                                echo '<td class="delimiter"></td>';
                            }
                            $i++;
                        }
                    }
                ?>
            </tr>
            </tr>
        </table>
    </body>
</html>
