<?
namespace Itlogic\Warehouse;

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

	public function addDeliveryId($id){

		$this->db->update('crm_deal_type_ref_stage',
			['delivery_id'=>'']);
		$item = $this->_getItem($id);

		if($item){
			$this->db->update('crm_deal_type_ref_stage',
				['delivery_id'=>$id],
				'WHERE deal_type_id = "'.$id.'"');
		}else{
			$this->_createItem($id,'');
		}
	}

	private function _createItem($deal_type_id,$deal_stage_ids){
		
		$this->db->insert('crm_deal_type_ref_stage',[
					'deal_type_id'=>$deal_type_id,
					'deal_stage_ids'=>$deal_stage_ids,					
					],'',false,'',false);

	}

	private function _updateItem($deal_type_id,$deal_stage_ids){

		$this->db->update('crm_deal_type_ref_stage',
			['deal_stage_ids'=>$deal_stage_ids],
			'WHERE deal_type_id = "'.$deal_type_id.'"');

	}

	private function _getItem($deal_type_id){

		$res = $this->db->query('SELECT * from crm_deal_type_ref_stage '.
			 ' WHERE deal_type_id = "'.$deal_type_id.'" ');
		$item = $res->fetch();
		
		return ($item) ? $item : 0;

	}

	public function store($deal_type_id,$deal_stage_ids){

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
			return [];
		}
	}

	// Сamasutra
	public function getStageOnlySelectedType($deal, $res){

		$deal_types = [];
		$deal_stages = [];

		while($ar = $res->Fetch())
		{
			if($ar['ENTITY_ID'] == 'DEAL_TYPE'){
				$deal_types[] = $ar;
			}else if($ar['ENTITY_ID'] == 'DEAL_STAGE'){
				$deal_stages[] = $ar;
			}
		}

		$ss = [];
		foreach($deal_types as $type)
		{
			if($type['STATUS_ID'] == $deal['TYPE_ID']){
				$ss['TYPE_ID'] = $type['ID'];
			}
		}

		$ids = $this->getTypeIds($ss['TYPE_ID']);
		$arrs = [];

		foreach($ids as $sts) {
			foreach($deal_stages as $stage){
				if($stage['ID'] == $sts){
					$arrs[$stage['STATUS_ID']] = $stage;
				}
			}
		}

		$res = [];
		foreach($arrs as $item){
			$res[$item['SORT']] = $item;
		}

		ksort($res);
		$data = [];

		foreach($res as $i){
			$data[$i['STATUS_ID']] = $i;
		}

		return $data;
	}

}