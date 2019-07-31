<?php

use spitfire\Model;
use spitfire\storage\database\Schema;

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

class QuestModel extends Model
{
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		
		
		$schema->color = new EnumField('bronze', 'silver', 'gold', 'yellow', 'amber', 'red');
		$schema->icon = new FileField();
		$schema->name = new StringField(50);
		$schema->description = new StringField(255);
		
		/*
		 * The classname and count indicate the amount of activity with the given
		 * classname the user needs to collect in order to receive the badge.
		 */
		$schema->activityName = new StringField(20);
		$schema->threshold = new IntegerField(true);
		
		/*
		 * Indicates whether the badge is "per value" of interactions. Sometimes activity
		 * can be measured in a "per value" or "per instance".
		 * 
		 * For example, an online shop may reward a customer with a badge if they
		 * bought 35 times (per instance) or because their total checkout was more
		 * than USD100 in the last month.
		 */
		$schema->perValue = new BooleanField();
		
		/*
		 * Some badges are given to users "at birth", meaning they receive the badge
		 * when the user account is created and, usually, the badge expires afterwards.
		 */
		$schema->birthRight = new BooleanField();
		
		/*
		 * If the TTL is set, the system must only count the events given in the 
		 * TTL as valid. For example, if the user is required to receive 20 monthly
		 * comments to be "conversation starter", then the system must make sure 
		 * that the badge is removed when the 20th comment is older than a month.
		 */
		$schema->ttl = new IntegerField(true);
	}

}
