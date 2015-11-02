<?php
		print("Start");
                $ftp = ftp_connect("merchantftp.buyseasons.com");
                if ($ftp && @ftp_login($ftp, "s3", "rZySZ4Mt")) {

                     ftp_pasv($ftp, false);


                     print("ОК");

                } else {
                        print("Could not open host");
                }
  
?>