<?php 

Class Steps extends Illuminate\Database\Eloquent\Model{
	protected $table = 'step';
	protected $primaryKey = 'id_step';

	//desactivar created_at updated_at
	public $timestamps = false;
}

?>