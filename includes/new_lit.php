<?php

    ?>
    <div class="wrap">
        <h1>New Litrature</h1>
        <div id="post-body-content">
        <?php
        if(strpos($_SERVER['HTTP_REFERER'],"/admin.php?page=new_lit.php") !== false 
            && $_POST["pdf_name"] != ""
            && $_POST["filename"] != ""
            && $_POST["status"] != ""
        ) {
            global $wpdb;
  
            $pdf_name= $_POST["pdf_name"];
            $caption = $_POST["caption"];
            $filename = $_POST["filename"];
            $status = $_POST["status"];
            $last_updated = date("Y-m-d");

            if($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM file WHERE filename = %s AND draft = %s", $filename, $status)) === "0"){

                //Move the files to relevent space
                $current_directory = ABSPATH . '/wp-content/uploads/pdf_unassigned/' . $filename;
                if($status == 1){
                    rename($current_directory, ABSPATH . '/wp-content/uploads/pdf_drafts/' . $filename);
                }
                else{
                    rename($current_directory, ABSPATH . '/wp-content/uploads/pdf_live/' . $filename);
                }
                $wpdb->insert( 
                    'file', 
                    array( 
                        'pdf_name' => $pdf_name, 
                        'caption' => $caption,
                        'filename' => $filename,
                        'last_updated' => $last_updated,
                        'draft' => $status
                    ), 
                    array( 
                        '%s', 
                        '%s',
                        '%s', 
                        '%s',
                        '%d'
                    ) 
                );
                //Insert into database 
                echo "<script>window.location.replace('/wp-admin/admin.php?page=litrature.php&add=true');</script>";

            }else{
                echo "<div class='error_lit small_form'> File already exists in that table</div> ";
            }
        }
        //Query to retrieve un-checked suggestions            
        $dir    = '../wp-content/uploads/pdf_unassigned/';
        $files1 = array_slice(scandir($dir), 2);
        $filename = $_GET["file"];

        ?>
        <form method="post" enctype="multipart/form-data" action="#" class="small_form">
            <div id="titlediv">
                <div id="poststuff">
                    <div id="bitesize_headlines_link" class="postbox" >
                        <h2 class="hndle ui-sortable-handle" style="cursor: auto;"><span>Literature information</span></h2>
                        <div class="inside">
                            <br>
                            <label>PDF Name</label>
                            <input name="pdf_name" required type="text" class="widefat">
                            <br><br>
                            <label >File Name</label>
                            <select id="filename" name="filename" required type="text" class="widefat">
                                <option></option>
                                <?php
                                    foreach ($files1 as $document) {
                                        if (strpos($document, '.pdf') !== false) {
                                            echo "<option value='".$document."' ";
                                            if( $_GET["file"] === $document){
                                                echo "selected";
                                            }
                                            echo ">".$document."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <br><br>
                            <label >Caption (Leave Blank if none)</label>
                            <input name="caption" type="text" class="widefat">
                            <br><br>
                            <label>Live or Draft?</label>
                            <input class="spacer" required type="radio" name="status" value="0"> Live
                            <input class="spacer" required type="radio" name="status" value="1"> Draft
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

        </script>
    </div>

    

