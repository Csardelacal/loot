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

class HistoryModel extends Model
{
	
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		$schema->user = new Reference(UserModel::class);
		
		
		$schema->balance = new IntegerField();
		$schema->effective = new IntegerField(true);
		$schema->created = new IntegerField(true);
		
		$schema->index($schema->user, $schema->effective);
	}
	
	public function onbeforesave() {
		if (!$this->created) {
			$this->created = time();
		}
	}
	
	public static function snapshot($dbu, $time) {
		$history = db()->table('history')->get('effective', $time, '<')->where('user', $dbu)->setOrder('effective', 'DESC')->first();
		$query = db()->table('score')->get('user', $dbu);
		
		if ($history) {
			$query->where('created', '>', $history->effective);
		}
		
		$query->where('created', '<', $time);
		
		return ($history? $history->balance : 0) + $query->all()->extract('score')->add([0])->sum();
	}

}
