<?php

class cssLib extends BitBase {

	function list_css($path, &$back) {
		$handle = opendir($path);

		while ($file = readdir($handle)) {
			if (is_dir($path.'/'.$file) and $file <> ".." and $file <> "." and $file <> "CVS") {
				$back = $this->list_css($path.'/'.$file, $back);
			} elseif ((substr($file, -4, 4) == ".css") and (ereg("^[-_a-zA-Z0-9\.]*$", $file))) {
				array_push($back, substr($path.'/'.$file, strlen(BIT_ROOT_PATH)));
			}
			unset($file);
		}
		closedir($handle);
		unset($handle);

		return $back;
	}

	function browse_css($path) {
		if (!is_file($path)) {
			return array("error" => "No such file : $path");
		}

		$meat = implode("", file($path));

		$find[0] = "/\}/";
		$repl[0] = "\n}\n";

		$find[1] = "/\{/";
		$repl[1] = "\n{\n";

		$find[2] = "/\/\*/";
		$repl[2] = "\n/*\n";

		$find[3] = "/\*\//";
		$repl[3] = "\n*/\n";

		$find[4] = "/;/";
		$repl[4] = ";\n";

		$find[5] = "/(W|w)hite/";
		$repl[5] = "#FFFFFF";

		$find[6] = "/(B|b)lack/";
		$repl[6] = "#000000";

		$res = preg_replace($find, $repl, $meat);
		return array(
			"error" => '',
			"content" => split("\n", $res)
		);
	}

	function parse_css($data) {
		$back = array();

		$index = 0;
		$type = '';

		foreach ($data as $line) {
			$line = trim($line);

			if ($line) {
				if (($type != "comment") and ($line == "/*")) {
					$type = "comment";

					$index++;
					$back["$index"]["comment"] = '';
					$back["$index"]["items"] = array();
					$back["$index"]["attributes"] = array();
				} elseif (($type == "comment") and ($line == "*/")) {
					$type = "";
				} elseif ($type == "comment") {
					$back["$index"]["comment"] .= "$line\n";
				} elseif (($type == "items") and ($line == "{")) {
					$type = "attributes";
				} elseif ($type == "items") {
					$li = split(",", $line);

					foreach ($li as $l) {
						$l = trim($l);

						if ($l)
							$back["$index"]["items"][] = $l;
					}
				} elseif (($type == "attributes") and ($line == "}")) {
					$type = "";

					$index++;
					$back["$index"]["comment"] = '';
					$back["$index"]["items"] = array();
					$back["$index"]["attributes"] = array();
				} elseif ($type == "attributes") {
					$parts = split(":", str_replace(";", "", $line));

					if (isset($parts[0]) && isset($parts[1])) {
						$obj = trim($parts[0]);

						$back["$index"]["attributes"]["$obj"] = trim($parts[1]);
					}
				} else {
					$li = split(",", $line);

					foreach ($li as $l) {
						$l = trim($l);

						if ($l)
							$back["$index"]["items"][] = $l;
					}

					$type = "items";
				}

				$back["content"] = $line;
			}
		}

		return $back;
	}

	// Load CSS2 styled file (@import aware)
	//
	// TODO: Will M$ windowz eat '/' as path delimiter?
	//
	function load_css2_file($filename) {
		$data = '';

		$path = dirname($filename);
		if (!file_exists($filename)) {
			return;
		}

		$lines = file($filename);
		foreach ($lines as $line) {
			if (preg_match_all("/@import.*\([\'|\" ]*(.*)[\'|\" ]*\).*;/U", $line, $importfiles, PREG_SET_ORDER)) {
				foreach ($importfiles as $file) {
					$fileName = trim( $file[1] );
					if( $fileName != '../../base.css' ) {
						$import = $path.'/'.$fileName;
						$data .= $this->load_css2_file($import);
						$line = str_replace($file[0], "", $line);
					}
				}
			}

			// TODO: Does it matter what $line may contain smth before '@import'? :)
			$data .= $line;
		}

		return $data;
	}
}

global $csslib;
$csslib = new cssLib();

?>
