<?php

use spitfire\mvc\Director;

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

class ScoreDirector extends Director
{
	
	/**
	 * Balances all the user's accounts that have not been balanced in the last 
	 * month.
	 */
	public function balance() {
		
		/*
		 * The user has a balanced field that allows the database to quickly look
		 * up which users require new balancing. If the user does, we will quickly
		 * query their data and generate a new score.
		 */
		$pending = db()->table('user')->get('balanced', time() - 86400 * 30, '<')->all();
		
		/*
		 * The effective variable allows all the user accounts to be balanced considering
		 * the given point in time. This is not specially critical in the case of 
		 * reputation management, but could lead to misunderstandings.
		 */
		$effective = time() - 1;
		$since = time() - (86400 * 365 * 2);
		
		console()->info(sprintf('Found %s user accounts that require balancing'))->ln();
		
		foreach($pending as $user) {
			/*
			 * Calculate the score for the user. Since the score expires after two 
			 * years, the application must use all the history it has available.
			 * 
			 * NOTE: In order to optimize this process, the application could use a
			 * record from two years ago and subtract the historical value from the 
			 * current one. But it's not completely trivial, so we're leaving it for
			 * a potential improved version.
			 */
			$score = db()->table('score')->get('user', $user)->where('created', '>', $since)->where('created', '<', $effective)->all()->extract('score')->sum();
			
			/*
			 * Generate a new historical record. The application can use these to 
			 * improve the lookup speed when fetching a user profile.
			 */
			$history = db()->table('history')->newRecord();
			$history->user = $user;
			$history->score = $score;
			$history->effective = $effective;
			$history->created = time();
			$history->store();
			
			/*
			 * Mark the user has balanced, and let them go on with their life.
			 */
			$user->balanced = time();
			$user->store();
		}
		
		console()->success('Balanced all pending users')->ln();
		
		
	}
	
}
