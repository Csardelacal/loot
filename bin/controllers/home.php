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

class HomeController extends PrivilegedController
{
	
	/**
	 * 
	 */
	public function index() {
		
		if ($this->user) {
			$dbu = db()->table('user')->get('_id', $this->user->id)->first();
			if (!$dbu) { $dbu = UserModel::make($this->sso->getUser($this->user->id)); }
			
			/*
			 * Retrieve the data necessary to assemble a small dashboard for the user
			 * to view his data and history.
			 */
			$badges = db()->table('badge')->get('user', $dbu)->where('expires', '>', time())->all();

			$history = db()->table('history')->get('user', $dbu)->setOrder('created', 'DESC')->first();

			/*
			 * This query is a bit more delicate, since it will use the result of the 
			 * previous query to assemble the score.
			 */
			$query = db()->table('score')->get('user', $dbu);

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
			
			$this->view->set('badges', $badges);
			$this->view->set('score', $score);
		}
		else {
			$this->response->setBody('Redirecting...')->getHeaders()->redirect(url('account', 'login'));
		}
	}
	
}
