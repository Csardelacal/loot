<?php

use spitfire\core\http\URL;
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
 * A quest determines the requirements a user needs to meet in order to receive
 * a certain badge. Quests can only be created, listed and modified by administrative
 * users with appropriate permissions.
 * 
 * @author César de la Cal Bretschneider <cesar@magic3w.com>
 */
class QuestController extends PrivilegedController
{
	
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
	 * This simply lists the quests available for the administrative user to 
	 * manage them. The administrator is then able to add quests, edit them, or
	 * delete them altogether.
	 * 
	 * 
	 */
	public function index() {
		$query = db()->table('quest')->getAll();
		$pages = new \spitfire\storage\database\pagination\Paginator($query);
		
		$this->view->set('pages', $pages);
		$this->view->set('quests', $pages->records());
	}
	
	/**
	 * 
	 * @validate >> POST#color(string required in[bronze, silver, gold, green, red])
	 * @validate >> POST#name(string required length[3, 50])
	 * @validate >> POST#description(string required length[3, 255])
	 * @validate >> POST#activityName(string required length[3, 20])
	 * @validate >> POST#threshold(number required)
	 * @validate >> POST#ttl(number)
	 * 
	 * @param QuestModel $quest
	 */
	public function edit(QuestModel$quest = null) {
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException('Not POSTed'); }
			if (!$this->validation->isEmpty()) { throw new ValidationException('Validation failure', 1907311827, $this->validation->toArray()); }
			
			$record = $quest? : db()->table('quest')->newRecord();
			$record->color = $_POST['color'];
			$record->icon = $_POST['icon'] instanceof spitfire\io\Upload? $_POST['icon']->store()->uri() : null;
			$record->name = $_POST['name'];
			$record->description = $_POST['description'];
			$record->activityName = $_POST['activityName'];
			$record->threshold = $_POST['threshold'];
			$record->awardTo = $_POST['awardTo'];
			$record->perValue = isset($_POST['perValue']);
			$record->birthRight = isset($_POST['birthRight']);
			$record->ttl = empty($_POST['ttl'])? null : $_POST['ttl'];
			$record->store();
			
			$this->response->setBody('Redirecting...')->getHeaders()->redirect(url('quest', 'edit', $record->_id));
		} 
		catch (HTTPMethodException$e) {
			//Do nothing, show the form
		}
		catch (spitfire\validation\ValidationException$e) {
			$this->view->set('messages', $e->getResult());
		}
		
		$this->view->set('record', $quest);
	}
	
	/**
	 * Delete a quest from the pool of available ones. This method requires the 
	 * use of XSRF to prevent the user from being sent via a script directly to 
	 * this endpoint to delete the quest.
	 * 
	 * @param QuestModel $quest
	 */
	public function delete(QuestModel$quest) {
		
		$xsrf = new spitfire\io\XSSToken();
		
		if (isset($_GET['confirm']) && $xsrf->verify($_GET['confirm'])) {
			$quest->delete();
			$this->response->setBody('Redirecting...')->getHeaders()->redirect(url('quest'));
		}
		
		$this->view->set('xsrf', $xsrf);
	}
	
	public function detail($id) {
		$this->view->set('badge', db()->table('quest')->get('_id', $id)->first(true));
	}
}
