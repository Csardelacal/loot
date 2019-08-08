<?php

use spitfire\Model;
use spitfire\storage\database\Schema;

class InteractionModel extends Model
{
	
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		
		$schema->src = new Reference(UserModel::class);
		$schema->tgt = new Reference(UserModel::class);
		
		/*
		 * The name indicates the type of activity that was recorded. Loot
		 * should then determine which score the user should receive for performing
		 * said action.
		 */
		$schema->name = new StringField(20);
		$schema->value = new IntegerField();
		
		/*
		 * This is for the user to identify the sources of their reputation.
		 */
		$schema->caption = new StringField(255);
		$schema->url = new StringField(4096);
		
		/*
		 * Records the time the record was created. This will be used to slowly 
		 * purge the history and/or limit the amount of karma that the user can
		 * collect.
		 */
		$schema->created = new IntegerField(true);
		
		/*
		 * At first we will only record the fact that the record came in, after that,
		 * a cronjob should process it and award the appropriate badges, score etc.
		 */
		$schema->processed = new IntegerField(true);
		
		/*
		 * Due to the amount of queries involving the user and the time, we index 
		 * the two fields together.
		 */
		$schema->index($schema->tgt, $schema->name, $schema->created);
		$schema->index($schema->processed);
	}

}