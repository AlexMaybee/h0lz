<?
namespace Itlogic\Multidealtype;

class NewDealTypeDao {

	public $db;
	public function __construct(){

		global $DB;
		$this->db = $DB;

	}

	public function getDeliveryId($id){

		$item = $this->_getItem($id);
		return $item['delivery_id'];
	}

	// public function addDeliveryId($id){

	// 	$this->db->update('crm_deal_type_ref_stage',
	// 		['delivery_id'=>''], '', '', true, true);
	// 	$item = $this->_getItem($id);
	// 	if($item){
	// 		$test = $this->db->update('crm_deal_type_ref_stage',
	// 			['delivery_id'=>$id],
	// 			'WHERE deal_type_id = "'.$id.'"');
	// 	// var_dump($test);
	// 	}else{
	// 		$this->_createItem($id,'');
	// 	}
	// }
	public function delete($deal_type_id){
		$result = $this->db->query('DELETE FROM crm_deal_type_ref_stage WHERE `deal_type_id` = '.$deal_type_id.'');
		return $result->Fetch();
	}

	public function addDeliveryId($id){

		// $this->db->query(' UPDATE crm_deal_type_ref_stage SET `deal_stage_ids` = "'.$deal_stage_ids.'" WHERE deal_type_id = "'.$deal_type_id.'"');
		$item = $this->_getItem($id);
		if($item){
			$test = $this->db->query(' UPDATE crm_deal_type_ref_stage SET `delivery_id` = "'.$id.'" WHERE deal_type_id = "'.$id.'"');
		// var_dump($test);
		}else{
			$this->_createItem($id,'');
		}
	}

	private function _createItem($deal_type_id, $deal_stage_ids){
		$r = $this->db->query('INSERT INTO crm_deal_type_ref_stage(`deal_type_id`, `deal_stage_ids`) VALUES ('.$deal_type_id.','.$deal_stage_ids.')');
		return $r->Fetch();
	}

	private function _updateItem($deal_type_id, $deal_stage_ids){
		$r = $this->db->query(' UPDATE crm_deal_type_ref_stage SET `deal_stage_ids` = '.$deal_stage_ids.' WHERE deal_type_id = '.$deal_type_id);
		return $r->Fetch();
	}
	// public function _createItem($deal_type_id,$deal_stage_ids){

	// 	$this->db->insert('crm_deal_type_ref_stage',[
	// 				'deal_type_id'=>$deal_type_id,
	// 				'deal_stage_ids'=>$deal_stage_ids,
	// 				]);

	// }
	// private function _updateItem($deal_type_id,$deal_stage_ids){

	// 	$this->db->update('crm_deal_type_ref_stage',
	// 		['deal_stage_ids'=>$deal_stage_ids],
	// 		'WHERE deal_type_id = "'.$deal_type_id.'"');

	// }

	public function _getItem($deal_type_id){

		$res = $this->db->query('SELECT * from crm_deal_type_ref_stage '.
			 ' WHERE deal_type_id = "'.$deal_type_id.'" ');
		$item = $res->fetch();

		return ($item) ? $item : 0;

	}

	public function test(){
		$res = $this->db->query('SELECT * from crm_deal_type_ref_stage ');
		while($item = $res->fetch()){
			$result[] = $item;
		}
		return ($result) ? $result : false;
	}

	public function store($deal_type_id, $deal_stage_ids){
		$item = $this->_getItem($deal_type_id);

		if($item == 0){
			$this->_createItem($deal_type_id,$deal_stage_ids);
		}else {
			$this->_updateItem($deal_type_id,$deal_stage_ids);
		}
	}

	public function getTypeIds($id){

		$item = $this->_getItem($id);
		if($item){
			$ids = explode(',', $item['deal_stage_ids']);
			return $ids;
		}else {
			return array();
		}
	}

	// Сamasutra
	public function getStageOnlySelectedType($deal, $res){

		$deal_types = array();
		$deal_stages = array();

		while($ar = $res->Fetch())
		{
			if($ar['ENTITY_ID'] == 'DEAL_TYPE'){
				$deal_types[] = $ar;
			}else if($ar['ENTITY_ID'] == 'DEAL_STAGE'){
				$deal_stages[] = $ar;
			}
		}

		$ss = array();
		foreach($deal_types as $type)
		{
			if($type['STATUS_ID'] == $deal['TYPE_ID']){
				$ss['TYPE_ID'] = $type['ID'];
			}
		}

		$ids = $this->getTypeIds($ss['TYPE_ID']);
		$arrs = array();
		$iii = 1;
		foreach($ids as $sts) {
			foreach($deal_stages as $stage){
				if($stage['ID'] == $sts){
					$arrs[$stage['STATUS_ID']] = $stage;

					// if($stage['STATUS_ID'] == 'WON' || $stage['STATUS_ID'] == 'LOSE'){
					// 	continue;
					// }
					// var_dump($stage['STATUS_ID']);
					$arrs[$stage['STATUS_ID']]['SORT'] = $iii;
					// $arrs[$stage['STATUS_ID']]['SYSTEM'] = 'Y';
					$iii = $iii + 1;
				}
			}
		}

		// $arrs[18]['SORT'] = 135;
		// $res = [];
		// foreach($arrs as $item){
		// 	$res[$item['SORT']] = $item;
		// }
		// ksort($res);
		// $data = [];

		// foreach($res as $i){
		// 	$data[$i['STATUS_ID']] = $i;
		// }
		// echo "sdfdsfsdfdsfddddddddddddddd";
		// echo "<pre>";
		// print_r($arrs);
		// echo "data<br>";
		// print_r($data);
		// echo "</pre>";
		return $arrs;
		// return $data;
	}


	public function getStagesByDealType($res){
		$deal_types = array();
		$deal_stages = array();
		while($ar = $res->Fetch())
		{
			if($ar['ENTITY_ID'] == 'DEAL_TYPE'){
				$deal_types[] = $ar;
			}else if($ar['ENTITY_ID'] == 'DEAL_STAGE'){
				$deal_stages[] = $ar;
			}
		}

		$all = array();
		$all['deal_types'] = $deal_types;
		$all['deal_stages'] = $deal_stages;

		$typeIds = array();

		foreach ($deal_types as $type) {
			$typeIds[] = $type['ID'];
		}

		return $typeIds;
	}

	public static function boobs(){
		return "BOOBS";
	}

}