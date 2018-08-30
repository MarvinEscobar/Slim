<?php 



Class Credit extends Illuminate\Database\Eloquent\Model{

	protected $table = 'credits';

	protected $primaryKey = 'id_credits';



	//desactivar created_at updated_at

	public $timestamps = false;

}



Class CreditDetail extends Illuminate\Database\Eloquent\Model{

	protected $table = 'credit_detail';

	protected $primaryKey = 'id_credit_detail';



	//desactivar created_at updated_at

	public $timestamps = false;

}



Class CreditPaymentDate extends Illuminate\Database\Eloquent\Model{

	protected $table = 'credit_payment_date';

	protected $primaryKey = 'id_credit_payment_date';



	//desactivar created_at updated_at

	public $timestamps = false;

}



Class CreditInstallmentStatus extends Illuminate\Database\Eloquent\Model{

	protected $table = 'credit_installment_status';

	protected $primaryKey = 'id_credit_installment_status';



	//desactivar created_at updated_at

	public $timestamps = false;

}



Class Periods extends Illuminate\Database\Eloquent\Model{

	protected $table = 'periods_payment';

	protected $primaryKey = 'id_periods_payment';



	//desactivar created_at updated_at

	public $timestamps = false;

}



Class AccountsPay extends Illuminate\Database\Eloquent\Model{

	protected $table = 'accounts_pay';

	protected $primaryKey = 'id_accounts_pay';



	//desactivar created_at updated_at

	public $timestamps = false;

}















?>