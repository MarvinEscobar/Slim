<?php 

Class Users extends Illuminate\Database\Eloquent\Model{
	protected $table = 'users';
	protected $primaryKey = 'id_user';

	//desactivar created_at updated_at
	public $timestamps = false;
}

?>