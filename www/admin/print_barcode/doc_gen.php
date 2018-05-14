<?php
    ini_set("display_errors",1);
    error_reporting(E_ALL & ~E_NOTICE);
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=print_barcode.doc");
?>
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head><title>Print Barcode</title>
<style><!-- 
    @page
    {
        size: 21cm 29.7cm;
        margin: 4mm 0mm 0mm 4mm;
        mso-page-orientation: portrait;  
    }
    @page section1 { }
    div.section1 { page: section1; }
    table {
        border-collapse: collapse;
    }
    .cell {
        width: 65mm;
        height: 25mm;
        border: solid 1px #000;
        text-align: center;
        vertical-align: middle;
    }
    .cell img {
        width: 46mm;
        height: 11mm;
    }
    .img {
        width: 46mm;
        height: 11mm;
    }
    .delimiter {
        width: 3mm;
        border: solid 1px #000;
    }
--></style>
</head>
    <body>
        <div class="section1">
        <table>
            <tr>
                <?php
                    move_uploaded_file($_FILES['file']['tmp_name'],"barcodes.txt");
                    $file=fopen('barcodes.txt','r');
                    $i=1;
                    while(!feof($file)){
                        $str=fgets($file);
                        $fields=explode(chr(9),$str);
                        for($j=0;$j<$fields[2];$j++){
                            echo '<td class="cell">';
                            echo '<img width="230" height="55" src="http://www.scandit.com/wp-content/themes/bridge-child/wbq_barcode_gen.php?symbology=ean13&value='.$fields[1].'&ec=L&size=100" />';
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
        </table>
        </div>
    </body>
</html>