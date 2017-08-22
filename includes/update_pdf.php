<!-- Check the address has a fileid if not redirect back to litrature page 
        Couldnt use header redirect here as brought up errors-->
<script type="text/javascript">
    var checker = "<?php echo $_GET["fileid"]; ?>";
        if( checker === ""){
             window.location = "/wp-admin/admin.php?page=litrature.php";
        }
</script>

<div class="wrap">
    <h1>Update PDF</h1>
    <?php
        global $wpdb;
        $file_id = $_GET["fileid"];
        $result = $wpdb->get_row($wpdb->prepare( "SELECT * FROM file WHERE file_id = %d", $file_id ) );

        if (isset($_POST['update'])) {
            $pdf_name= $_POST["pdf_name"];
            $current = $_POST["current"];
            $last_updated = date("Y-m-d");
            $status = $_POST["status"];
            $target_dir = ABSPATH . "/wp-content/uploads/pdf_drafts/";
            $pdf = $_FILES['new_pdf'];
            $target_file = $target_dir . $current;

            if($status === "1"){             
                if (move_uploaded_file($_FILES["new_pdf"]["tmp_name"], $target_file)) {
                  echo " <br/><div class='success_lit small_form'> PDF was updated </div>";
                } 
                else {
                    echo "Sorry, there was an error uploading your file.";
                    die();
                }
                $wpdb->update( 
                        'file', 
                        array( 'last_updated' => $last_updated), 
                        array( 'file_id' => $file_id), 
                        array( '%s'), 
                        array( '%d' ) 
                    );
            }else if($status === "0"){
               $draft_check = $wpdb->get_row($wpdb->prepare( "SELECT file_id FROM file WHERE filename = %s AND draft = 1", $current ) );
                if($draft_check->file_id !== NULL){
                    if (move_uploaded_file($_FILES["new_pdf"]["tmp_name"], $target_file)) {
                      echo " <br/><div class='success_lit small_form'> PDF was updated on the draft version of this record</div>";
                    } 
                    else {
                        echo "Sorry, there was an error uploading your file.";
                        die();
                    }

                    $wpdb->update( 
                            'file', 
                            array( 'last_updated' => $last_updated, 'filename' => $current), 
                            array( 'file_id' => $draft_check->file_id), 
                            array( '%s'), 
                            array( '%d' ) 
                        );
                }
                else if($draft_check->file_id === NULL){
                    $result = $wpdb->get_row($wpdb->prepare( "SELECT * FROM file WHERE file_id = %d", $file_id ) );
                    if (move_uploaded_file($_FILES["new_pdf"]["tmp_name"], $target_file)) {
                      echo " <br/><div class='success_lit small_form'> PDF was updated on a new draft </div>";
                    } 
                    else {
                        echo "Sorry, there was an error uploading your file.";
                        die();
                    }
                    $wpdb->insert( 
                    'file', 
                    array( 'pdf_name' => ($result->pdf_name), 'caption' => ($result->caption),'filename' => $current, 'last_updated' => $last_updated, 'draft' => 1 ), 
                    array( '%s', '%s','%s', '%s' )
                );
                }
            }
        }

        ?>
    <div id="post-body-content">
    <form enctype="multipart/form-data" method="post" action="#" onsubmit="return checkMe();" class="small_form">
        <div id="titlediv">
            <div id="poststuff">
                <div id="bitesize_headlines_link" class="postbox" >
                    <h2 class="hndle ui-sortable-handle" style="cursor: auto;"><span>File Upload</span></h2>
                    <div class="inside">
                    <p class="updatePDFlabel">This will keep the current filename to allow urls not to break</p>
                    <input type='hidden' name='file_id' value="<?php echo $result->file_id; ?>">
                    <input type='hidden' name='status' value="<?php echo $result->draft; ?>">
                    <label>Current PDF</label>
                    <input id="current" name="current" type='text' readonly class="widefat" value="<?php echo $result->filename; ?>"></input>  
                    <br><br>
                    <label>New PDF</label>
                    <input id="new" name="new" type='text' readonly class="widefat" ></input>  
                    <br><br>
                    <input id="new_pdf"  name="new_pdf" required type="file" multiple /><br/><br/>
                    <button id="update" name='update' class='button-primary button-litrature'>Update</button><br/><br/>
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

        document.getElementById("update").disabled = true;

        document.getElementById('new_pdf').onchange = function () {
            document.getElementById("new").value = this.value.replace(/.*[\/\\]/, '');
            var new_pdf = document.getElementById('new_pdf').value.replace(/.*[\/\\]/, '' );
            var current= document.getElementById('current').value;
            if(new_pdf.includes(".pdf") !== true){
                alert("File need to be a PDF");
                document.getElementById("new_pdf").value = "";
                document.getElementById("update").disabled = true;
                document.getElementById("new").value = "";
            }
            else{
                if(new_pdf != current){
                    var r = confirm("These are different filename, are these the correct files? (as the new file will take on the old files name)");
                    if (r == true) {
                            document.getElementById("update").disabled = false;
                    }
                    else{
                            document.getElementById("new_pdf").value = "";
                            document.getElementById("update").disabled = true;
                            document.getElementById("new").value = "";
                    }
                }
                
            }
        }


         function checkMe() {
            if (confirm("Are you sure you want to replace the existing file? (As this will delete or replace the previous file if not used elsewhere.)")) {
                return true;
            } else {
                return false;
            }
        }

    </script>
</div>
