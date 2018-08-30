<?php 

Class PhoneData extends Illuminate\Database\Eloquent\Model{
	protected $table = 'phone_data';
	protected $primaryKey = 'id_phone_data';

	//desactivar created_at updated_at
	public $timestamps = false;
}

?>