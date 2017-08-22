<div class="wrap">
    <h1>Unassigned Literature</h1>
        <?php

        if(strpos($_SERVER['HTTP_REFERER'],"/admin.php?page=unassigned.php") !== false && $_POST["filename"] != "") {
            $filename = $_POST["filename"]; //Pull filename from previous page
            $file_directory = ABSPATH . '/wp-content/uploads/pdf_unassigned/'.$filename; // Sets up directory to remove from
            unlink($file_directory); //delete it   
            $dir    = '../wp-content/uploads/pdf_unassigned/';
            $files1 = array_slice(scandir($dir), 2);
            $count = count($files1); 
            echo "There is <strong>$count</strong> pieces of unassigned Literature<br/><br/>"; 
            echo "<div class='success_lit small_form'>File has been successfully deleted </div> <br/>";
        } else{
            $dir    = '../wp-content/uploads/pdf_unassigned/';
            $files1 = array_slice(scandir($dir), 2);
            $count = count($files1); 

            echo "There is <strong>$count</strong> pieces of unassigned Literature<br/><br/>"; 
        }            
        ?>
        <table class="wp-list-table widefat fixed striped small_form">
        <thead>
            <tr>
                <th scope="col" id="" colspan="4" class="manage-column">
                    File name
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        if($count === 0){
            echo "<tr>
                    <td class='colspanchange' colspan='4'> There are no unassigned files </td>
                </tr>";
        }
        else{
            for ($x = 0; $x < $count; $x++) {
            echo "<tr>
                    <td class='colspanchange' colspan='2'>".$files1[$x]."</td>
                    <td class='colspanchange' colspan='1'>
                    <form method='post' action='/wp-admin/admin.php?page=new_lit.php&file=".$files1[$x]."'>
                        <input type='hidden' name='filename' value=".$files1[$x].">
                        <button class='button-primary'>Add</button>
                    </form>
                    </td>
                    <td class='colspanchange' colspan='1'>
                    <form method='post' action='#' onClick='return checkMe()'>
                        <input type='hidden' name='filename' value='".$files1[$x]."'>
                        <button class='button-primary'>
                            Delete
                        </button>
                    </form>
                    </td>
                </tr>";
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="col" id="" colspan="4" class="manage-column">
                    File name
                </th>
            </tr>
        </tfoot>
    </table>
</div>
<script language="javascript">
    function checkMe() {
        if (confirm("Are you sure you want to delete this file?")) {
            return true;
        } else {
            return false;
        }
    }
</script>