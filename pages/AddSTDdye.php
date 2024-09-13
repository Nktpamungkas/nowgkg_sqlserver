<?php
mysqli_query($con, "INSERT INTO tbl_stdmcdye (`id`) 
				VALUES ('')");
echo "<script type=\"text/javascript\">
            alert(\"Data Berhasil Ditambah\");
            window.location = \"StdLodingDYE\"
            </script>";
?>