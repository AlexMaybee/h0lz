<?
namespace Itlogic\Warehouse;

class WarehouseDao {

	public $db;
	public function __construct(){

		global $DB;
		$this->db = $DB; 

	}

	public function createItems($item_id, $count, $check = 1){

		$this->db->insert('crm_warehouse',[
			'item_id'=>$item_id,
			'item_count'=>$count,
			'check'=>$check,
			],'',false,'',true);

		return true;
	}

	public function updateItems($item_id, $count, $check = 1){

		$this->db->update('crm_warehouse',
			['item_count'=>$count,
				'check'=>$check],
			'WHERE item_id = "'.$item_id.'"');

		return true;
	}


	private function __updateReserveItem($item_id, $count, $owner_id, $type){

		$this->db->update('crm_product_ref_owner',
				[
				 'reserve_count'=>$count,
				 'item_id'=> $item_id,
				 'type'=>'"'.$type.'"'
				 ],' WHERE owner_id = "'.$owner_id.'" AND type = "'.$type.'" AND item_id = "'.$item_id.'" ','', true);

	}

	private function __createReserveItems($item_id, $count, $owner_id, $type){
	
		$this->db->insert('crm_product_ref_owner',
			[
				'item_id'=>$item_id,
				'reserve_count'=>$count,
				'owner_id'=>$owner_id,
				'type'=> '"'.$type.'"'
			],'',true,'',false);	

	}

	public function reserveItems($item_id, $count, $owner_id, $type = 'enather'){


		$owner = $this->__getOwner($item_id, $owner_id, $type);

		if(!$owner){
			$this->__createReserveItems($item_id, $count, $owner_id, $type);
		}else{
			$this->__updateReserveItem($item_id, $count, $owner_id, $type);
		}

	}

	public function  unReserveItem($item_id,$owner_id,$type = 'enather'){

		$res =  $this->db->query("DELETE FROM crm_product_ref_owner WHERE item_id=$item_id AND owner_id=$owner_id AND type='".$type."' ");
		return $res->fetch();

	}

	public function updateItemsCount($item_id, $count, $check=1 ){

		$item = $this->__getItem($item_id);
		
		if($item){
			$this->updateItems($item_id, $count, $check);
		} else {
			$this->createItems($item_id, $count, $check);
		}

		return true;
	}

	public function getAllCountsItems ($item_id){

		$item = $this->__getItem($item_id);
		return $item['item_count'];

	}

	public function getAvailableItemCount($item_id,$ownerID,$type = 'enather'){

		$item = $this->__getItem($item_id);
		if($item['check'] == 0){
			return 'not_check';
		}

		if($ownerID){

			$res = $this->db->query("SELECT ".
			"(SELECT reserve_count FROM crm_product_ref_owner".
			" WHERE owner_id = '".$ownerID."' ".
			" AND type = '".$type."' AND item_id = '".$item_id."' GROUP BY reserve_count ) AS owner ,".
			" SUM(reserve_count) as sum ".
			" FROM crm_product_ref_owner ".
			" WHERE item_id = '".$item_id."' ");
			$row = $res->fetch();
			$avaliable = $item['item_count'] - ($row['sum'] - (int)$row['owner']);

		} else {

			$res = $this->db->query('SELECT SUM(reserve_count) as sum'. 
			' FROM crm_product_ref_owner '.
			' WHERE item_id = "'.$item_id.'"');
			$row = $res->fetch();
			$avaliable = $item['item_count'] - $row['sum']; 
		
		}
		
		return $avaliable;
	}

	public function getAvailableForProductList($item_id,$ownerID,$type = 'enather'){

		$item = $this->__getItem($item_id);
		if($ownerID){

			$res = $this->db->query("SELECT ".
				"(SELECT reserve_count FROM crm_product_ref_owner".
				" WHERE owner_id = '".$ownerID."' ".
				" AND type = '".$type."' AND item_id = '".$item_id."' GROUP BY reserve_count ) AS owner ,".
				" SUM(reserve_count) as sum ".
				" FROM crm_product_ref_owner ".
				" WHERE item_id = '".$item_id."' ");
			$row = $res->fetch();
			$avaliable = $item['item_count'] - ($row['sum'] - (int)$row['owner']);

		} else{

			$res = $this->db->query('SELECT SUM(reserve_count) as sum'.
				' FROM crm_product_ref_owner '.
				' WHERE item_id = "'.$item_id.'"');
			$row = $res->fetch();
			$avaliable = $item['item_count'] - $row['sum'];

		}

		return $avaliable;


	}

	public function getItemById($id){
		return $this->__getItem($id);
	}

	public function getReservedCount($item_id){

		$item = $this->__getItem($item_id);
		return $item['item_reserve'];

	}

	private function __getItem($item_id){

		$res =	$this->db->query('SELECT * '.
			'FROM crm_warehouse WHERE item_id = "'.$item_id.'"');
		$item = $res->fetch();
		if(!$item){
			return 0;
		}else {
			return $item;
		}

	}

	public function deleteProduct($id){

		$res = $this->db->query('DELETE FROM crm_warehouse WHERE item_id="'.$id.'" ');		
		$res->fetch();

	}

	public function deleteReserveByType($owner_id,$type){

		$res = $this->db->query
		('DELETE FROM crm_product_ref_owner '.
		 ' WHERE owner_id="'.$owner_id.'" AND type="'.$type.'"');

		 $res->fetch();

	}

	private function __getOwner($item_id, $owner_id, $type){

		$res =	$this->db->query('SELECT item_id , owner_id, reserve_count , type '.
			' FROM crm_product_ref_owner WHERE owner_id = "'.$owner_id.'" '.
			' AND type = "'.$type.'" AND item_id = "'.$item_id.'" ');

		$item = $res->fetch();

		if(!$item){
			return false;
		}else{
			return $item;
		}
	}

	public function finishDeal($id, $type, $flag){

		$res = $this->db->query('SELECT ref.*, f.item_count FROM crm_product_ref_owner as ref '.
								'left join crm_warehouse as f  ON f.item_id = ref.item_id '.
								'WHERE ref.owner_id = "'.$id.'" AND ref.type = "'.$type.'"');

		while($item = $res->fetch()){
			if(!$flag){
				$this->db->update('crm_warehouse',
					['item_count'=>($item['item_count']-$item['reserve_count'])],
					'WHERE item_id = "'.$item['item_id'].'"');

			}else{
				$this->db->update('crm_warehouse',
					['item_count'=>($item['item_count']+$item['reserve_count'])],
					'WHERE item_id = "'.$item['item_id'].'"');

				$this->deleteReserveByType($item['owner_id'],$type);
			}
		}
	}
}