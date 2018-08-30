<?php 
 


Class Accounts extends Illuminate\Database\Eloquent\Model{

	protected $table = 'accounts';

	protected $primaryKey = 'id_accounts';



	//desactivar created_at updated_at

	public $timestamps = false;

}



Class SecureCode extends Illuminate\Database\Eloquent\Model{

	protected $table = 'security_code';

	protected $primaryKey = 'id_security_code';



	//desactivar created_at updated_at

	public $timestamps = false;

}



Class ConfirmCode extends Illuminate\Database\Eloquent\Model{

	protected $table = 'confirm_code';

	protected $primaryKey = 'id_confirm_code';



	//desactivar created_at updated_at

	public $timestamps = false;

}





Class Credits extends Illuminate\Database\Eloquent\Model{

	protected $table = 'credits';

	protected $primaryKey = 'id_credits';



	//desactivar created_at updated_at

	public $timestamps = false;

}

Class AccountSession extends Illuminate\Database\Eloquent\Model{

	protected $table = 'account_session';

	protected $primaryKey = 'id_account_session';



	//desactivar created_at updated_at

	public $timestamps = false;

}


Class Ranking extends Illuminate\Database\Eloquent\Model{

	protected $table = 'ranking';

	protected $primaryKey = 'id_ranking';



	//desactivar created_at updated_at

	public $timestamps = false;

}


Class AccountPenalties extends Illuminate\Database\Eloquent\Model{

	protected $table = 'account_penalties';

	protected $primaryKey = 'id_account_penalties';


	//desactivar created_at updated_at

	public $timestamps = false;

}










?>