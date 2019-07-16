<?php

/* 
 * The MIT License
 *
 * Copyright 2019 César de la Cal Bretschneider <cesar@magic3w.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use spitfire\Model;
use spitfire\storage\database\Schema;

class ScoreModel extends Model
{
	
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		
		$schema->interaction = new Reference(InteractionModel::class);
		
		/*
		 * These are denormalized views to increment the performance of the queries
		 * on this model.
		 */
		$schema->user = new Reference(UserModel::class);
		$schema->rule = new Reference(RewardModel::class);
		
		/*
		 * The score awarded for this action (this may be negative)
		 */
		$schema->score = new IntegerField();
		
		/*
		 * Records the time the record was created. This will be used to slowly 
		 * purge the history and/or limit the amount of karma that the user can
		 * collect.
		 */
		$schema->created = new IntegerField(true);
		
		/*
		 * Due to the amount of queries involving the user and the time, we index 
		 * the two fields together.
		 */
		$schema->index($schema->user, $schema->created);
	}

}