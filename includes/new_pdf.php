<?php
if(isset($_FILES['new_pdf'])){
    $pdf = $_FILES['new_pdf'];
    $target_dir = ABSPATH . "/wp-content/uploads/pdf_unassigned/";
    $target_file = $target_dir . basename($_FILES["new_pdf"]["name"]);

    if (move_uploaded_file($_FILES["new_pdf"]["tmp_name"], $target_file)) {
        echo "<script>window.location.replace('/wp-admin/admin.php?page=new_lit.php&file=" . $_FILES["new_pdf"]["name"] . "');</script>";
        header("Location: /wp-admin/admin.php?page=new_lit.php&file=" . $_FILES["new_pdf"]["name"]);
    } else {
        echo "Sorry, there was an error uploading your file.";
        die();
    }
  }
?>
<div class="wrap">
    <h1>Upload PDF</h1>
    <div id="post-body-content">
    <?php
    if($_GET["exist"] == true){
            echo "<div class='error_lit small_form'> File already exists in that table</div> ";
        }
    $filename = $_GET["file"];
    ?>
    <form method="post" enctype="multipart/form-data" action="#" class="small_form">
        <div id="titlediv">
            <div id="poststuff">
                <div id="bitesize_headlines_link" class="postbox" >
                    <h2 class="hndle ui-sortable-handle" style="cursor: auto;"><span>Upload Information</span></h2>
                    <div class="inside">
                    <br/>
                    <label>New PDF</label>
                    <input id="new" name="new" type='text' required readonly class="widefat" ></input>  
                    <br/>
                    <br/>
                        <input type='file' id='new_pdf' name='new_pdf'></input> 
                        <?php submit_button('Submit') ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
    <script type="text/javascript">
        //Make it look like it still in the litratuere section
        var d = document.getElementById("toplevel_page_litrature");
            d.className += " wp-has-current-submenu";
        var e = d.getElementsByTagName("a")[0];
            e.className += " wp-has-current-submenu";

        document.getElementById("submit").disabled = true;

        document.getElementById('new_pdf').onchange = function () {
        var val = document.getElementById('new_pdf').value.replace(/.*[\/\\]/, '' ); 
            // Check to see if the file they put in is a pdf
            if(val.includes(".pdf") !== true){
                alert("File need to be a PDF");
                document.getElementById("new_pdf").value = "";
                document.getElementById("submit").disabled = true;
                document.getElementById("new").value = "";
            }
            else{
                document.getElementById("new").value = val;
                document.getElementById("submit").disabled = false;
            }
        };
    </script>
</div>