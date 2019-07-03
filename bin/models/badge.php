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

class BadgeModel extends Model
{
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		
		$schema->color = new EnumField('bronze', 'silver', 'gold');
		$schema->icon = new FileField();
		$schema->name = new StringField(255);
		$schema->description = new StringField(255);
		
		/*
		 * The classname and count indicate the amount of activity with the given
		 * classname the user needs to collect in order to receive the badge.
		 */
		$schema->activityName = new StringField(20);
		$schema->count = new IntegerField(true);
		
		/*
		 * Some badges are given to users "at birth", meaning they receive the badge
		 * when the user account is created and, usually, the badge expires afterwards.
		 */
		$schema->givenAtBirth = new BooleanField();
		
		/*
		 * If the TTL is set, the system must only count the events given in the 
		 * TTL as valid. For example, if the user is required to receive 20 monthly
		 * comments to be "conversation starter", then the system must make sure 
		 * that the badge is removed when the 20th comment is older than a month.
		 */
		$schema->ttl = new IntegerField(true);
	}

}
