<?php 
if (!defined('ABSPATH')) { exit; }
if(!class_exists('wpSmartImportUpload')){
	class wpSmartImportUpload {
			
		static function wpsi_file_upload() {
			// Check for Security if current request Not from ajax Or nonce is not match  die this request
			$rnonce = isset( $_REQUEST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $rnonce, 'wpsi_nonce' ) ) {
				wp_die( esc_html__( 'Security check failed. Please try again.', 'wp-smart-import' ) );
			}
			wpSmartImportCommon::verify_ajax( $rnonce );
			$response = array('response' => "ERROR", 'msg' => 'File Not Found');
			$extension_array = array('xml');
			$upload_dir = wp_upload_dir();
			$wpsi_fd_path = $upload_dir["basedir"]. "/". wpSmartImport::getVar('folder_name') . "/";
			$request = wpsi_helper::recursive_sanitize_text_field($_POST);
			if (isset($request['file_from']) && $request['file_from'] == 'download') {
				$file = esc_url_raw($request['file']);
				preg_match( '/[^\?]+\.(xml)\b/i', $file, $matches );
 
				if ( ! $matches ) {
					$response['msg'] = "File url is not valid";
					echo json_encode( $response );
					wp_die();
				}
				// check remote file is Exist and responce code == 200
				if (wp_remote_retrieve_response_code(wp_safe_remote_get($file)) == 200) {
					$path = explode("?", $file);
					$file_data = pathinfo(trim($path[0]));
					$file_name  = sanitize_file_name(str_replace(" ", "_", basename($path[0])));
					if (isset($file_data['extension']) && in_array($file_data['extension'], $extension_array)) {
						$new_folder = uniqid();
				    	$destination = $wpsi_fd_path. $new_folder ."/"; 
				    	if (!file_exists($destination)) {
							mkdir($destination, 0777, true);
						}
						$destination_path = $destination.$file_name;
						$status = self::download_file($file, $destination_path);
						if ($status) {
							$file_size = filesize($destination_path);
							$response = array(
								'response' 	=> "SUCCESS",
								'msg' 		=> "File is Ready to use",
								'filename' 	=> $file_name,
								'file_size' => self::format_size_units($file_size),
								'type'		=> $file_data['extension'],
								'filepath' 	=> $new_folder.'/'.$file_name
							);
						} else {
							$response['msg'] = "File Download Error";
						}
					} else {
						$response['msg'] = "File is Not Valid" ;
					}
				}
			} else {
				$fileErrors = array(
				    0 => 'There is no error, the file uploaded with success',
				    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
				    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
				    3 => 'The uploaded file was only partially uploaded',
				    4 => 'No file was uploaded',
				    6 => 'Missing a temporary folder',
				    7 => 'Failed to write file to disk.',
				    8 => 'A PHP extension stopped the file upload.',
				);
				$file_data = isset($_FILES) ? $_FILES : array();
				$data = array_merge($_REQUEST, $file_data);

				if (!empty($data) && is_array($data)) {
					global $wp_filesystem;

					if (empty($wp_filesystem)) {
						require_once ABSPATH . '/wp-admin/includes/file.php';
						WP_Filesystem();
					}
					
					// Check if WP_Filesystem initialization was successful
					if (!$wp_filesystem) {
						// WP_Filesystem initialization failed, handle error
						$response['msg'] = "Failed to initialize WP_Filesystem";
						$response["response"] = "ERROR";
					} else {
						// WP_Filesystem initialization successful, proceed with file operations
						$xml_temp_file = $data['wpsi_file_upload']['tmp_name'];
					
						// Check if the file exists
						if ($wp_filesystem->exists($xml_temp_file)) {
							// Fetch the file contents
							$xml_content = $wp_filesystem->get_contents($xml_temp_file);
					
							// Sanitize XML content
							$sanitized_xml_content = self::sanitize_xml($xml_content);
							
							// Extract file information
							$f_data = pathinfo($data['wpsi_file_upload']['name']);
							
							// Check if file extension is valid
							if (isset($f_data['extension']) && in_array($f_data['extension'], $extension_array)) {
								$new_folder = uniqid();
								$upload_path = $wpsi_fd_path . $new_folder . "/";
					
								// Create the upload directory using WP_Filesystem
								if (!$wp_filesystem->is_dir($upload_path)) {
									$wp_filesystem->mkdir($upload_path, 0777);
								}
					
								// Prepare sanitized file name
								$fileName = sanitize_file_name(str_replace(" ", "_", $data["wpsi_file_upload"]["name"]));
					
								// Define target path
								$targetPath = $upload_path . $fileName;
					
								// Save the sanitized XML content to the target path using WP_Filesystem
								if ($wp_filesystem->put_contents($targetPath, $sanitized_xml_content, FS_CHMOD_FILE) !== false) {
									// File saving successful
									$response['msg'] = "File Ready to run";
									$response["response"] = "SUCCESS";
									$response["filename"] = $fileName;
									$response["filepath"] = $new_folder . '/' . $fileName;
									$response["file_size"] = self::format_size_units($wp_filesystem->size($targetPath));
									$response["type"] = $f_data['extension']; // Assuming you want to include the file extension
								} else {
									// Error occurred while saving the file
									$response["response"] = "ERROR";
									$response["msg"] = "Failed to save the file.";
								}
							} else {
								// Invalid file extension
								$response['msg'] = "File extension is not valid";
								$response["response"] = "ERROR";
							}
						} else {
							// File does not exist
							$response['msg'] = "File does not exist";
							$response["response"] = "ERROR";
						}
					}
				} else {
					// No valid data found
					$response['msg'] = "No valid data found";
				}
			}
			echo json_encode( $response );
			wp_die();
		}

		static function sanitize_xml($xml_content) {
			// Load XML securely
			$dom = new DOMDocument();
			$dom->recover = true; // Enable recovery mode to handle parsing errors gracefully
			$dom->strictErrorChecking = false; // Disable strict error checking to prevent errors on malformed XML

			$dom->loadXML( $xml_content, LIBXML_PARSEHUGE );

			// Check for parsing errors
			if (libxml_get_last_error() !== false) {
				// Log or handle parsing errors
				error_log("XML sanitization error: " . libxml_get_last_error()->message);
				libxml_clear_errors();
				return false;
			}
		
			// Remove script elements, including namespaced and nested ones
			$xpath = new DOMXPath($dom);
			$scripts = $xpath->query('//script | //*[namespace-uri() != "" and local-name() = "script"]');
			foreach ($scripts as $script) {
				$script->parentNode->removeChild($script);
			}
		
			// Remove JavaScript event attributes
			foreach ($xpath->query('//@*[starts-with(name(), "on")]') as $attr) {
				$attr->ownerElement->removeAttributeNode($attr);
			}
		
			// Encode user-controlled data (attributes and text content)
			$nodes = $dom->getElementsByTagName('*');
			foreach ($nodes as $node) {
				foreach ($node->attributes as $attr) {
					$attr->value = htmlspecialchars( $attr->value, ENT_QUOTES | ENT_XML1, 'UTF-8' );
				}
				if ($node->nodeType === XML_TEXT_NODE) {
					$node->nodeValue = htmlspecialchars( $node->nodeValue, ENT_QUOTES | ENT_XML1, 'UTF-8' );
				}
			}
		
			// Return sanitized XML content
			return $dom->saveXML();
		}


		/**
		 * Download helper to download files in chunks and save it.
		 * 
		 * @param  string  $srcName      Source Path/URL to the file you want to download
		 * @param  string  $dstName      Destination Path to save your file
		 * @param  integer $chunkSize    (Optional) How many bytes to download per chunk (In MB). Defaults to 1 MB.
		 * @param  boolean $returnbytes  (Optional) Return number of bytes saved. Default: true
		 * 
		 * @return integer               Returns number of bytes delivered.
		 */
		static function download_file($srcName, $dstName, $chunkSize = 1, $returnbytes = true) {
		  $chunksize = $chunkSize*(1024*1024); // How many bytes per chunk
		  $data = '';
		  $bytesCount = 0;
		  $handle = fopen($srcName, 'rb');
		  $fp = fopen($dstName, 'w');
		  if ($handle === false) {
		    return false;
		  }
		  while (!feof($handle)) {
		    $data = fread($handle, $chunksize);
			$sanitized_data = self::sanitize_xml($data);
		    if (fwrite($fp, $sanitized_data, strlen($sanitized_data	)) == false){
		    	return false;
		    }
		    
		    if ($returnbytes) {
		        $bytesCount += strlen($data);
		    }
		  }
		  $status = fclose($handle);
		  fclose($fp);
		  if ($returnbytes && $status) {
		    return $bytesCount; // Return number of bytes delivered like readfile() does.
		  }
		  return $status;
		}

		static function format_size_units($bytes) {
		    if ($bytes >= 1073741824) {
		        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
		    }
		    elseif ($bytes >= 1048576) {
		        $bytes = number_format($bytes / 1048576, 2) . ' MB';
		    }
		    elseif ($bytes >= 1024) {
		        $bytes = number_format($bytes / 1024, 2) . ' KB';
		    }
		    elseif ($bytes > 1) {
		        $bytes = $bytes . ' bytes';
		    }
		    elseif ($bytes == 1) {
		        $bytes = $bytes . ' byte';
		    }
		    else{
		        $bytes = '0 bytes';
		    }
		    return $bytes;
		}
	}
}