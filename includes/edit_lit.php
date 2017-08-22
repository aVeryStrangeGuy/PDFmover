<!-- Check the address has a fileid if not redirect back to litrature page 
        Couldnt use header redirect here as brought up errors-->
<script type="text/javascript">
    var checker = "<?php echo $_GET["fileid"]; ?>";
        if( checker === ""){
             window.location = "/wp-admin/admin.php?page=litrature.php";
        }
</script>
    <div class="wrap">
        <h1>Edit Litrature</h1>

<?php
    global $wpdb; 

    if (isset($_POST['update'])) {
        // Variables
        $pdf_name= $_POST["pdf_name"];
        $caption = $_POST["caption"];
        $status = $_POST["status"];
        $file_id = $_POST["file_id"];
        $last_updated = date("Y-m-d");
        $filename = $_POST["filename"];

        if($status === "1"){
            $wpdb->update( 
                    'file', 
                    array( 'pdf_name' => $pdf_name, 'caption' => $caption, 'filename' => $filename, 'last_updated' => $last_updated, 'draft' => $status), 
                    array( 'file_id' => $file_id), 
                    array( '%s', '%s', '%s', '%s', '%d'), 
                    array( '%d' ) 
                );
            echo " <br/><div class='success_lit small_form'> Records were updated </div>";
        }else if($status === "0"){
            $result = $wpdb->get_row( $wpdb->prepare("SELECT * FROM file WHERE filename = %s AND draft = 1", $filename));
            //Deal with databse side
            $count = count($result); 
             if($count === 1){
                $wpdb->update( 
                    'file', 
                    array( 'pdf_name' => $pdf_name, 'caption' => $caption, 'filename' => $filename, 'last_updated' => $last_updated, 'draft' => 1), 
                    array( 'filename' => $filename, 'draft' => 1), 
                    array( '%s', '%s', '%s', '%s'), 
                    array( '%d' ) 
                );

                echo " <br/><div class='success_lit small_form'> Records were updated in the draft version of this record</div>";

             }
             else if($count === 0){
                $wpdb->insert( 
                    'file', 
                    array( 'pdf_name' => $pdf_name, 'caption' => $caption,'filename' => $filename, 'last_updated' => $last_updated, 'draft' => 1 ), 
                    array( '%s', '%s','%s', '%s' )
                );
                $current_directory = ABSPATH . '/wp-content/uploads/pdf_live/' . $filename;
                copy($current_directory, ABSPATH . '/wp-content/uploads/pdf_drafts/' . $filename);

                echo " <br/><div class='success_lit small_form'> Records were updated in the newly created draft version of this</div>";
            }

        }
    }

    $file_id = $_GET["fileid"];
    $result = $wpdb->get_row($wpdb->prepare( "SELECT * FROM file WHERE file_id = %d", $file_id ) );
    ?>
        <div id="post-body-content">
        <form method="post" enctype="multipart/form-data" action="#" class="small_form">
            <div id="titlediv">
                <div id="poststuff">
                    <div id="bitesize_headlines_link" class="postbox" >
                        <h2 class="hndle ui-sortable-handle"><span>Literature information</span></h2>
                        <div class="inside">
                            <br>
                            <input type='hidden'  name='file_id' value="<?php echo $result->file_id; ?>">
                            <input type='hidden'  name='status' value="<?php echo $result->draft; ?>">
                            <label>PDF Name</label>
                            <input name="pdf_name" required type="text" class="widefat" value="<?php echo $result->pdf_name; ?>">
                            <br><br>
                            <label >File Name (To edit this use the Update PDF button)</label><br/>
                            <input id="filename" readonly name="filename" required type="text" class="widefat filename" value="<?php echo $result->filename; ?>">
                            <a class='button-primary button-litrature' href="/wp-admin/admin.php?page=update_pdf.php&fileid=<?php echo $file_id; ?>">Update PDF</a>
                            <br><br>
                            <label >Caption (Leave Blank if none)</label>
                            <input name="caption" type="text" class="widefat" value="<?php echo $result->caption; ?>">
                            <br><br>
                           

                            <button name='update' class='button-primary button-litrature'>Update</button><br/><br/>
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

        </script>
    </div>

    

