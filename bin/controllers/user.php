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

class UserController extends BaseController
{
	
	/**
	 * Returns the information the system holds on a user. This includes their
	 * username, score and badges they have earned.
	 * 
	 * This endpoint does not provide any feedback on the progress a single user
	 * is making towards a badge.
	 */
	public function profile($username) {
		
		$remote = $this->sso->getUser($username);
		
		if (!$remote) {
			throw new PublicException('No user found', 404);
		}
		
		$user = db()->table('user')->get('_id', $remote->getId())->first()? : UserModel::make($remote);
		$badges = db()->table('badge')->get('user', $user)->where('expires', '>', time())->all();
		
		$history = db()->table('history')->get('user', $user)->setOrder('created', 'DESC')->first();
		
		/*
		 * This query is a bit more delicate, since it will use the result of the 
		 * previous query to assemble the score.
		 */
		$query = db()->table('score')->get('user', $user);
		
		if ($history) {
			$query->where('created', '>', $history->effective);
		}
		
		/*
		 * Calculate the appropriate score for the user. Plase note that, if the 
		 * user does have no history, we do assume that they started with a 0 reputation.
		 * 
		 * Currently, loot does not support users having any offset reputation from
		 * the default. Except by scripting webhooks to award users a score as soon
		 * as they register.
		 */
		$score = ($history? $history->balance : 0) + $query->all()->extract('score')->add([0])->sum();
		
		/*
		 * Export the calculated data to the view.
		 */
		$this->view->set('profile', $remote);
		$this->view->set('user', $user);
		$this->view->set('score', $score);
		$this->view->set('badges', $badges);
	}
	
}
