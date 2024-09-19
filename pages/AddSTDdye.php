<?php
sqlsrv_query($con, "INSERT INTO dbnow_gkg.dbnow_gkg.tbl_stdmcdye
                                (no_item, jenis_kain, lebar, gramasi, knit_std, loading, tgl_update)
                                VALUES(NULL, NULL, NULL, NULL, NULL, NULL, NULL);");
echo "<script type=\"text/javascript\">
            alert(\"Data Berhasil Ditambah\");
            window.location = \"StdLodingDYE\"
            </script>";
?>