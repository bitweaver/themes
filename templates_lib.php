<?php

class TemplatesLib extends BitBase {
	function TemplatesLib() {				
	BitBase::BitBase();
	}

	function list_all_templates($offset, $maxRecords, $sort_mode, $find) {
		$bindvars = array();
		if ($find) {
			$bindvars[] = '%' . $find . '%';
			$mid = " where (`content` like ?)";
		} else {
			$mid = "";
		}

		$query = "select `name`,`created`,`template_id` from `".BIT_DB_PREFIX."tiki_content_templates` $mid order by ".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_content_templates` $mid";
		$result = $this->mDb->query($query,$bindvars,$maxRecords,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$query2 = "select `section` from `".BIT_DB_PREFIX."tiki_content_templates_sections` where `template_id`=?";
			$result2 = $this->mDb->query($query2,array((int)$res["template_id"]));
			$sections = array();
			while ($res2 = $result2->fetchRow()) {
				$sections[] = $res2["section"];
			}
			$res["sections"] = $sections;
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	/*shared*/
	function get_template($template_id) {
		$query = "select * from `".BIT_DB_PREFIX."tiki_content_templates` where `template_id`=?";
		$result = $this->mDb->query($query,array((int)$template_id));
		if (!$result->numRows()) return false;
		$res = $result->fetchRow();
		return $res;
	}

	function replace_template($template_id, $name, $content) {
		global $gBitSystem;
		$now = $gBitSystem->getUTCTime();
		$bindvars = array($content,$name,(int)$now);
		if ($template_id) {
			$query = "update `".BIT_DB_PREFIX."tiki_content_templates` set `content`=?, `name`=?, `created`=? where `template_id`=?";
			$bindvars[] = (int) $template_id;
		} else {
			$query = "delete from `".BIT_DB_PREFIX."tiki_content_templates` where `content`=? and `name`=?";
			$this->mDb->query($query,array($content,$name),-1,-1);
			$query = "insert into `".BIT_DB_PREFIX."tiki_content_templates`(`content`,`name`,`created`) values(?,?,?)";
		}

		$result = $this->mDb->query($query,$bindvars);
		$id = $this->mDb->getOne("select max(`template_id`) from `".BIT_DB_PREFIX."tiki_content_templates` where `created`=? and `name`=?",array((int)$now,$name));
		return $id;
	}

	function add_template_to_section($template_id, $section) {
		$this->mDb->query("delete from `".BIT_DB_PREFIX."tiki_content_templates_sections` where `template_id`=? and `section`=?",array((int)$template_id,$section),-1,-1);
		$query = "insert into `".BIT_DB_PREFIX."tiki_content_templates_sections`(`template_id`,`section`) values(?,?)";
		$result = $this->mDb->query($query,array((int)$template_id,$section));
	}

	function remove_template_from_section($template_id, $section) {
		$result = $this->mDb->query("delete from `".BIT_DB_PREFIX."tiki_content_templates_sections` where `template_id`=? and `section`=?",array((int)$template_id,$section));
	}

	function template_is_in_section($template_id, $section) {
		$cant = $this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_content_templates_sections` where `template_id`=? and `section`=?",array((int)$template_id,$section));
		return $cant;
	}

	function remove_template($template_id) {
		$query = "delete from `".BIT_DB_PREFIX."tiki_content_templates` where `template_id`=?";
		$result = $this->mDb->query($query,array((int)$template_id));
		$query = "delete from `".BIT_DB_PREFIX."tiki_content_templates_sections` where `template_id`=?";
		$result = $this->mDb->query($query,array((int)$template_id));
		return true;
	}
}

$templateslib = new TemplatesLib();

?>
