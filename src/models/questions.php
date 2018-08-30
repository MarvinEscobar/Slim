<?php 

Class Questions extends Illuminate\Database\Eloquent\Model{
	protected $table = 'questions';
	protected $primaryKey = 'id_questions';

	//desactivar created_at updated_at
	public $timestamps = false;
}

?>