<?php 



Class AnswersUser extends Illuminate\Database\Eloquent\Model{

	protected $table = 'answers_steps';

	protected $primaryKey = 'id_answers_steps';



	//desactivar created_at updated_at

	public $timestamps = false;

}



Class DefaultAnswers extends Illuminate\Database\Eloquent\Model{

	protected $table = 'default_answers';

	protected $primaryKey = 'id_default_answers';



	//desactivar created_at updated_at

	public $timestamps = false;

}



?>