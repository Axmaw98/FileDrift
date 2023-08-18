<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php


// Max size PER file in KB
$max_file_size="100512";

// Max size for all files COMBINED in KB
$max_combined_size="2048";

//Maximum file uploades at one time
$file_uploads="1";

//The name of your website
$websitename="FileDrift";

// Full browser accessable URL to where files are accessed. With trailing slash.
$full_url="http://Your_Website/up/uploads/";

// Path to store files on your server If this fails use $fullpath below. With trailing slash.
$folder="./uploads/";

// Use random file names? true=yes (recommended), false=use original file name.
// Random names will help prevent files being denied because a file with that name already exists.
$random_name=true;

// Types of files that are acceptiable for uploading. Keep the array structure.
$allow_types=array("jpg","gif","png","zip","rar","txt","doc","bmp","tif","swf","ico","pdf","mp3","wma","ogg","wmv","avi","doc","mid","docx", "svg");

// Only use this variable if you wish to use full server paths. Otherwise leave this empty. With trailing slash.
$fullpath="";

//Use this only if you want to password protect your upload form.
$password="";


// Initialize variables
$password_hash = md5($password);
$error = "";
$success = "";
$display_message = "";
$file_ext = array();
$password_form = "";

// Function to get the extension of a file.
function get_ext($key) {
    $key = strtolower(substr(strrchr($key, "."), 1));
    $key = str_replace("jpeg", "jpg", $key);
    return $key;
}

// Filename security cleaning. Do not modify.
function cln_file_name($string)
{
    $cln_filename_find = [
        '/\s\s+/',         // any whitespace greater than one space
        '/[^\d\w\s-]/',    // anything not a letter, number, whitespace, or hyphen
        '/[\s-]+/'         // any whitespace or hyphen
    ];
    $cln_filename_repl = [
        ' ',
        '',
        '-'
    ];
    $string = match($string) {
        preg_replace('/.[^.]+$/','',$string) => preg_replace($cln_filename_find, $cln_filename_repl, $string),
        preg_replace('/\s\s+/',' ',$string) => preg_replace('/[-_]+/',' ',$string),
        default => trim($string),
    };
    return $string;
}

// If a password is set, they must login to upload files.
if (isset($password)) {

    // Verify the credentials.
    if (isset($_POST['verify_password'])) {
        if (md5($_POST['check_password']) == $password_hash) {
            setcookie("phUploader", $password_hash);
            sleep(1); //seems to help some people.
            header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Show the authentication form
    if (!isset($_COOKIE['phUploader']) || $_COOKIE['phUploader'] != $password_hash) {
        $password_form = "<form method=\"POST\" action=\"".$_SERVER['PHP_SELF']."\">\n";
        $password_form .= "<table align=\"center\" class=\"table\">\n";
        $password_form .= "<tr>\n";
        $password_form .= "<td width=\"100%\" class=\"table_header\" colspan=\"2\">Login</td>\n";
        $password_form .= "</tr>\n";
        $password_form .= "<tr>\n";
        $password_form .= "<td width=\"35%\" class=\"table_body\">Enter your Password بنووسە: </td>\n";
        $password_form .= "<td width=\"65%\" class=\"table_body\"><input type=\"password\" name=\"check_password\" /></td>\n";
        $password_form .= "</tr>\n";
        $password_form .= "<td colspan=\"2\" align=\"center\" class=\"table_body\">\n";
        $password_form .= "<input type=\"hidden\" name=\"verify_password\" value=\"true\">\n";
        $password_form .= "<input type=\"submit\" value=\"Login \" />\n";
        $password_form .= "</td>\n";
        $password_form .= "</tr>\n";
        $password_form .= "</table>\n";
        $password_form .= "</form>\n";
    }
}

// Don't allow submit if $password_form has been populated
if (isset($_POST['submit']) && $password_form == "") {

    // Tally the size of all the files uploaded, check if it's over the amount.
    if (array_sum(array_column($_FILES['file']['size'], 0)) > $max_combined_size * 1024) {

        $error .= "<b>Your attempt failed due to: </b><b>Total files too large<br/>";

        // Loop though, verify and upload files.
    } else {

		// Loop through all the files.
		for($i=0; $i <= $file_uploads-1; $i++) {

			// If a file actually exists in this key
			if(isset($_FILES['file']['name'][$i])) {

				//Get the file extension
				$file_ext[$i]=get_ext($_FILES['file']['name'][$i]);

				// Randomize file names
				if($random_name){
					$file_name[$i]=time()+rand(0,100000);
				} else {
					$file_name[$i]=cln_file_name($_FILES['file']['name'][$i]);
				}

				// Check for blank file name
				if(str_replace(" ", "", $file_name[$i])=="") {

					$error.= "<b>Your attempt failed: </b>"."Because your file is untitled.";

				//Check if the file type uploaded is a valid file type.
			}	elseif(!in_array($file_ext[$i], $allow_types)) {

					$error.= "<b>Your attempt failed: </b>"."Because your file type is not allowed to be uploaded.";

				//Check the size of each file
				} elseif($_FILES['file']['size'][$i] > ($max_file_size*1024)) {

					$error.= "<b>Your attempt failed: </b>"."due to the file's size exceeding the specified limit.";

				// Check if the file already exists on the server..
				} elseif(file_exists($folder.$file_name[$i].".".$file_ext[$i])) {

					$error.= "<b>Your attempt failed: </b>"."Because this file exists on the server.";

				} else {

					If(move_uploaded_file($_FILES['file']['tmp_name'][$i],$folder.$file_name[$i].".".$file_ext[$i])) {

						$success.="<b>  The uploaded file: </b> ".$_FILES['file']['name'][$i]."<br />"."<br>";
						$success .= '<div class="url-container"><p class="url"><b>Direct links: </b> <a href="' . $full_url . $file_name[$i] . "." . $file_ext[$i] . '" target="_blank">' . $full_url . $file_name[$i] . "." . $file_ext[$i] . '</a></p><span class="copy-url-icon" onclick="copyToClipboard(\'' . $full_url . $file_name[$i] . "." . $file_ext[$i] . '\', this)"><i class="far fa-copy"></i></span><div class="copy-popup"></div></div>';



					} else {
						$error.="<b>Your attempt failed: </b>"."Due to a problem during uploading.";
					}

				}

			} // If Files

		} // For

	} // Else Total Size

	if(($error=="") AND ($success=="")) {
		$error.="<b>Warning:</b> No files found <br/>";
	}

	$display_message=$success.$error;

} // $_POST AND !$password_form


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ckb" lang="cbk">
<head>
<link rel="shortcut icon" href="fav.ico" type="image/x-icon">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<title><?php echo $websitename; ?></title>
</head>
<body>


<?php
if($password_form) {
    echo $password_form;
} else {
?>

<form action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="phuploader">
    <table align="center" class="table">
        <tr>
            <td class="table_header" colspan="2"><b><?php if (isset($websitename)){echo $websitename;}?></b></td>
        </tr>

        <?php if($display_message) {?>
            <tr>
                <td colspan="2" class="message">
                    <br />
                    <?php echo $display_message;?>
                    <br />
                </td>
            </tr>
        <?php }?>

        <tr>
            <td colspan="2" class="upload_info">
                <b>Allowed file type: | <?php echo implode(", ", $allow_types);?></b>


                <p><br />
                    <b>  The maximum size allowed per file : </b>
                    <?php echo $max_file_size; ?> kb
                </p>
                <p><br />
                    <b> Total maximum file size allowed : </b>
                    <?php echo $max_combined_size; ?> kb <br />
                </p>
            </td>
        </tr>
        <?php for($i=0;$i <= $file_uploads-1;$i++) {?>
            <tr>
							                <td class="table_body" width="26%"><b>Choose the file:</b></td>
                <td class="table_body" width="74%"><b>
                    <input type="file" name="file[]" value="Select the file" size="30" />
                </b></td>

            </tr>
        <?php }?>
        <tr>
            <td colspan="2" align="center" class="table_footer">
                <input type="hidden" name="submit" value="true" />
                <input type="submit" value="Upload" /> &nbsp;
                <input type="reset" name="reset" value="Reset" onclick="window.location.reload(true);" />
            </td>
        </tr>
    </table>
</form>

<?php } ?>
<table class="table" style="border:0px;" align="center">
	<tr>
		<td><div class="copyright"><a href="https://github.com/Axmaw98" target="_blank">Powered by Ahmed Kawa</a></div></td><td><div class="copyright"><a href="https://github.com/Axmaw98/" target="_blank">© 2023 All Rights Reserved</a></div></td>
	</tr>
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script>
function copyToClipboard(text, icon) {
  var input = document.createElement('textarea');
  input.value = text;
  document.body.appendChild(input);
  input.select();
  document.execCommand('copy');
  document.body.removeChild(input);

  var tickIcon = icon.parentNode.querySelector('.tick-icon');
  if (!tickIcon) {
    tickIcon = document.createElement('i');
    tickIcon.classList.add('far', 'fa-check-circle', 'tick-icon');
    icon.parentNode.appendChild(tickIcon);
  }
  tickIcon.style.display = 'block';
  setTimeout(function() {
    tickIcon.style.display = 'none';
    icon.parentNode.classList.remove('show');
  }, 2000);
}

</script>

</body>
</html>
