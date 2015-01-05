<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopifyDeploymentConfigs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shopify_environments', function($table){
			$table->string('shop');
			$table->string('env');
			$table->integer('theme_id');
			$table->string('build_number');
			$table->primary(array('shop', 'env'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shopify_environments');
	}

}
