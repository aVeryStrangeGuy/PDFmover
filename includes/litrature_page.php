
    <div class="wrap">
        <h1>
            Literature 
        </h1> 
        <br/>
        <a href="/wp-admin/admin.php?page=new_pdf.php" class="page-title-action">Upload PDF</a>
        <br/><br/>
        <ul class="subsubsub">
            <li class="all">
                All <span class="count">(
                    <?php 
                        global $wpdb; 
                        echo $wpdb->get_var( "SELECT COUNT(*) FROM file" )
                    ?>
                )</span>
            </li>
        </ul>

        <br/> <br/> 

        <?php 
            $pdf_name = $_POST["pdf_name"];
            $caption = $_POST["caption"];
            $status = $_POST["status"];
            $filename = $_POST["filename"];
            $fileid = $_POST["file_id"];
            $last_updated = date("Y-m-d");

            if (isset($_POST['live'])) {
                $num_of_rows = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*)
                                                FROM file 
                                                WHERE filename = %s 
                                                AND draft = 0", $filename ));

                $current_directory = ABSPATH . "/wp-content/uploads/pdf_drafts/" . $filename;

                if($num_of_rows === "1"){
                    $wpdb->update( 
                        'file', 
                        array( 'pdf_name' => $pdf_name, 'caption' => $caption, 'filename' => $filename, 'last_updated' => $last_updated, 'draft' => 0), 
                        array( 'filename' => $filename, 'draft' => 0), 
                        array( '%s', '%s', '%s', '%s', '%d'), 
                        array( '%d', '%d') 
                    );
                    $wpdb->delete( 'file', array( 'file_id' => $fileid ),array('%d') );
                    rename($current_directory, ABSPATH . '/wp-content/uploads/pdf_live/'. $filename);
                    echo "<div class='success_lit'> The Record has been moved to live successfully </div> <br/>";
                }else if($num_of_rows === "0"){
                    $wpdb->update( 
                        'file', 
                        array( 'last_updated' => $last_updated, 'draft' => 0 ), 
                        array('file_id' => $fileid, 'draft' => 1 ), 
                        array( '%s', '%d'), 
                        array( '%d', '%d' ) 
                    );
                    rename($current_directory, ABSPATH . '/wp-content/uploads/pdf_live/'. $filename);
                    echo "<div class='success_lit'> The Record has been moved to live successfully </div> <br/>";
                }else{
                    echo "<div class='error_lit'> Multiple of same records found. Contact System Administrator. </div> <br/>";
                }
                
            }

            if (isset($_POST['delete'])) {
                $wpdb->delete( 'file', array( 'file_id' => $fileid ),array('%d') );
                $num_of_rows = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*)
                                                FROM file 
                                                WHERE filename = %s ", $filename ));
                if($num_of_rows === "0"){
                    if($status === "1"){
                        $file_directory = ABSPATH . '/wp-content/uploads/pdf_drafts/'.$filename; // Sets up directory to remove from
                        unlink($file_directory); //delete it
                      }
                      else{
                        $file_directory = ABSPATH . '/wp-content/uploads/pdf_live/'.$filename; // Sets up directory to remove from
                        unlink($file_directory); //delete it
                      }
                    }

                echo "<div class='success_lit'> File has been deleted and removed from table </div> <br/>";
            }

            if($_GET["add"] == true && strpos($_SERVER['HTTP_REFERER'],"/admin.php?page=new_lit.php") !== false){
                echo "<div class='success_lit'> New Litrature has been added </div> <br/>";
                
            }
            if($_GET["update"] == true){
                echo "<div class='success_lit'> The Record has been successfully updated </div> <br/>";
                
            }
            if($_GET["link"] == true){
                echo "<div class='error_lit'> Link already exists </div> <br/>";
                
            }
        ?>
    
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="id_column manage-column hide-columns">
                        Id
                    </th> 
                    <th scope="col" class="manage-column hide-columns">
                        File Name
                    </th> 
                    <th scope="col" colspan="1" class="manage-column">
                        PDF
                    </th>
                    <th scope="col" class="manage-column hide-columns">
                        Caption
                    </th> 
                    <th scope="col" class="manage-column hide-columns">
                        Last Updated
                    </th> 
                    <th scope="col" class="manage-column hide-columns">
                        Draft?
                    </th>
                    <th scope="col" colspan="2" class="manage-column hide-columns">
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $results = $wpdb->get_results("SELECT * FROM file ORDER BY filename;");
                    if($wpdb->get_var( "SELECT COUNT(*) FROM file" ) != 0){
                        foreach( $results as $key => $row) {

                            echo "<tr>
                                <td class='colspanchange' colspan='1'>".$row->file_id."</td>
                                <td class='colspanchange' colspan='1'>".$row->pdf_name."</td>";
                                if($row->draft == 1){
                                    echo "<td class='colspanchange' colspan='1'><a href ='/wp-content/uploads/pdf_drafts/".$row->filename."'>".$row->filename."</td>";
                                }else{
                                    echo "<td class='colspanchange' colspan='1'><a href ='/wp-content/uploads/pdf_live/".$row->filename."'>".$row->filename."</td>";
                                }
                            echo "<td class='colspanchange' colspan='1'>".$row->caption."</td>
                                  <td class='colspanchange' colspan='1'>".$row->last_updated."</td>
                                  <td class='colspanchange' colspan='1'>";
                            if($row->draft == 1){
                                echo "<img src='/wp-content/plugins/litrature_v3/tick.png'/>";
                            } 
                            echo "</td> 
                                    <td class='colspanchange' colspan='2'>
                                        <form method='post' action='/wp-admin/admin.php?page=edit_lit.php&fileid=".$row->file_id."'>
                                            <input type='hidden'  name='file_id' value=".$row->file_id.">
                                            <button class='button-primary button-litrature'>Edit</button>
                                        </form>
                                        <form method='post' action='#' onClick='return checkMe()'>
                                            <input type='hidden' name='file_id' value=".$row->file_id.">
                                            <input type='hidden' name='filename' value='".$row->filename."'>
                                            <input type='hidden' name='status' value=".$row->draft.">
                                            <button class='button-primary button-litrature' name='delete'>Delete</button>
                                        </form>
                                        <form method='post' action='/wp-admin/admin.php?page=update_pdf.php&fileid=".$row->file_id."'>
                                            <input type='hidden' name='filename' value='".$row->filename."''>
                                            <input type='hidden' name='file_id' value=".$row->file_id.">
                                            <input type='hidden' name='status' value=".$row->draft.">
                                            <button  class='button-primary button-litrature'>Update PDF</button>
                                        </form>";
                                        if($row->draft == 1){
                                    echo "<form method='post' action='#' onClick='return existInLive()'>
                                                <input type='hidden' name='pdf_name' value='".$row->pdf_name."'>
                                                <input type='hidden' name='caption' value='".$row->caption."'>
                                                <input type='hidden' name='filename' value='".$row->filename."'>
                                                <input type='hidden' name='file_id' value=".$row->file_id.">
                                                <button name='live' class='button-primary button-litrature'>Make Live</button>
                                            </form>";
                                        };
                            echo "</td></tr>";
                        }
                    }
                    else{
                        echo "<tr class='no-items'><td class='colspanchange' colspan='7'>No Literature items found</td></tr>";
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col" class="id_column manage-column hide-columns">
                        Id
                    </th> 
                    <th scope="col" class="manage-column hide-columns">
                        File Name
                    </th> 
                    <th scope="col" colspan="1" class="manage-column">
                        PDF
                    </th>
                    <th scope="col" class="manage-column hide-columns">
                        Caption
                    </th>
                    <th scope="col" class="manage-column hide-columns">
                        Last Updated
                    </th>
                    <th scope="col" class="manage-column hide-columns">
                        Draft?
                    </th>
                    <th scope="col" 
                    colspan="2" class="manage-column hide-columns">
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
<script language="javascript">
    function checkMe() {
        return confirm("Are you sure you want to delete this file? (This will also delete the PDF for the system if not used elsewhere)");
    }
    function existInLive() {
        return confirm("This will overwrite any copies of this file that are live are you sure you want to proced?");
    }
</script>