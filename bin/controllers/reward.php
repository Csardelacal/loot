<?php

use spitfire\exceptions\HTTPMethodException;
use spitfire\exceptions\PublicException;
use spitfire\validation\ValidationException;

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

/**
 * A reward defines the amount of "XP" that a user receives when they perform
 * or receive a certain type of interaction.
 * 
 * The system will require the administrator to provide a certain keyword (an
 * identifier) that it will match against the data it receives from other apps
 * that are pushing data towards it.
 * 
 * @author César de la Cal Bretschneider <cesar@magic3w.com>
 */
class RewardController extends PrivilegedController
{
	
	/**
	 * This controller can only be accessed by privileged users. The users can check
	 * their scores somewhere else. The rewards are not publicly announced by the 
	 * application.
	 * 
	 * @throws PublicException
	 */
	public function _onload() {
		parent::_onload();
		
		if (!$this->user) {
			$this->response->setBody('Redirect...')->getHeaders()->redirect(url('user', 'login', ['returnto' => URL::current()]));
		}
		
		if (!$this->isPrivileged) {
			throw new PublicException('Restricted to administrators only', 403);
		}
	}
	
	/**
	 * Lists the available rewards. 
	 */
	public function index() {
		$query = db()->table('reward')->getAll();
		$pages = new \spitfire\storage\database\pagination\Paginator($query);
		
		$this->view->set('pages', $pages);
		$this->view->set('rewards', $pages->records());
	}
	
	/**
	 * @validate >> POST#activity (string required length[3, 50])
	 * @validate >> POST#score (number required)
	 * @validate >> POST#awardTo (number required positive in[1, 2, 3])
	 * 
	 * @param RewardModel $reward
	 */
	public function edit(RewardModel$reward = null) {
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException('Not posted', 1908050853); }
			if (!$this->validation->isEmpty()) { throw new ValidationException('Validation failed', 1908050855, $this->validation->toArray()); }
			
			$record = $reward?: db()->table('reward')->newRecord();
			$record->activityName = $_POST['activity'];
			$record->score = $_POST['score'];
			$record->awardTo = $_POST['awardTo'];
			$record->perValue = isset($_POST['value']) && ($_POST['value'] === 'true');
			$record->store();
			
			$this->response->setBody('Redirecting...')->getHeaders()->redirect(url('reward', 'edit', $record->_id));
		}
		catch (HTTPMethodException$e) {
			//Show the form
		}
		catch (ValidationException$e) {
			$this->view->set('messages', $e->getResult());
		}
		
		$this->view->set('reward', $reward);
	}
	
	/**
	 * Deletes a reward. Once it is deleted it will no longer be awarded to users.
	 * 
	 * NOTE: Since the crons run asynchronously, it may take a few minutes until the
	 * crons are no longer awarding the selected reward. Equally, new rewards may
	 * take a cron restart (or waiting for them to end) to be start being awarded.
	 * 
	 * @param RewardModel $reward
	 */
	public function delete(RewardModel$reward) {
		
		$xsrf = new spitfire\io\XSSToken();
		
		if (isset($_GET['confirm']) && $xsrf->verify($_GET['confirm'])) {
			$reward->delete();
			$this->response->setBody('Redirecting...')->getHeaders()->redirect(url('reward'));
		}
		
		$this->view->set('xsrf', $xsrf);
	}
	
}
