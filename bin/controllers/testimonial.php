<?php

use spitfire\exceptions\HTTPMethodException;
use spitfire\exceptions\PublicException;
use spitfire\storage\database\pagination\Paginator;

/* 
 * The MIT License
 *
 * Copyright 2020 César de la Cal Bretschneider <cesar@magic3w.com>.
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
 * Testimonials allow users to provide feedback on other users. To ensure that
 * users are allowed to provide feedback on their interaction with another user,
 * the system requires that the testimonial is relayed from another application.
 * 
 * This could be a shop, game or anything the users interacted on.
 * 
 * @author César de la Cal Bretschneider <cesar@magic3w.com>
 */
class TestimonialController extends PrivilegedController
{
	
	/**
	 * 
	 * @validate POST#body(string length[10, 400])
	 * @validate POST#user(positive number required)
	 * @validate POST#product(url)
	 * 
	 * @throws PublicException
	 */
	public function create() {
		/*
		 * Only applications are allowed to push testimonials, this ensures that
		 * users cannot manipulate reviews - at least from Loot directly.
		 */
		if (!$this->authapp) {
			throw new PublicException('Sorry. You are not authorized ot provide a testimonial', 403);
		}
		
		$client = db()->table('user')->get('_id', $_POST['client'])->first();
		if (!$client && $_POST['client']) { $client = UserModel::make($this->sso->getUser($_POST['client'])); }
		
		$user = db()->table('user')->get('_id', $_POST['user'])->first();
		if (!$user) { $user = UserModel::make($this->sso->getUser($_POST['user'])); }
		
		/*
		 * Create the record. 
		 */
		$testimonial = db()->table('testimonial')->newRecord();
		$testimonial->body = $_POST['body'];
		$testimonial->user = $user;
		$testimonial->client = $client;
		$testimonial->recommendation = isset($_POST['recommendation']) && $_POST['recommendation'] !== false;
		$testimonial->product = $_POST['product'];
		$testimonial->store();
		
		$this->view->set('testimonial', $testimonial);
	}
	
	public function edit(TestimonialModel$testimonial) {
		/*
		 * Only applications are allowed to push testimonials, this ensures that
		 * users cannot manipulate reviews - at least from Loot directly.
		 */
		if (!$this->user) {
			throw new PublicException('Sorry. You are not authorized ot provide a testimonial', 403);
		}
		
		if ($this->user->id != $testimonial->client->_id) {
			throw new PublicException('Sorry, you are not allowed to edit other users testimonials', 403);
		}
		
		$profile = $this->sso->getUser($testimonial->user->_id);
		if (!$profile) { throw new PublicException('Not found', 404); }
		
		try {
			/*
			 * If the request wasn't posted we do not process the data coming from it.
			 */
			if (!$this->request->isPost()) { throw new HTTPMethodException('Not Posted'); }
			/*
			 * Create the record. 
			 */
			$testimonial->body = $_POST['body'];
			$testimonial->recommendation = isset($_POST['recommendation']) && $_POST['recommendation'] !== false;
			$testimonial->store();
			
			$this->response->setBody('Redirect...')->getHeaders()->redirect(url('testimonial', 'on', $profile->getUsername()));
		}
		catch (HTTPMethodException$e) {
			
		}
		
		$this->view->set('testimonial', $testimonial);
	}
	
	public function on($username) {
		
		$profile = $this->sso->getUser($username);
		if (!$profile) { throw new PublicException('Not found', 404); }
		
		$dbu = db()->table('user')->get('_id', $profile->getId())->first(true);
		$query = db()->table('testimonial')->get('user', $dbu)->setOrder('created', 'DESC');
		$pages = new Paginator($query);
		
		$this->view->set('pages', $pages);
		$this->view->set('testimonials', $pages->records());
	}
	
	public function reply(TestimonialModel$testimonial) {
		
		$profile = $this->sso->getUser($testimonial->user->_id);
		if (!$profile) { throw new PublicException('Not found', 404); }
		
		try {
			/*
			 * If the request wasn't posted we do not process the data coming from it.
			 */
			if (!$this->request->isPost()) { throw new HTTPMethodException('Not Posted'); }
			
			/*
			 * A user cannot reply to a testimonial they did not receive.
			 */
			if ($this->user->id !== $testimonial->user->_id) { throw new PublicException('Not allowed', 403); }
			
			$testimonial->response = $_POST['body'];
			$testimonial->store();
			
			if ($testimonial->client) {
				$this->ping->activity($this->user->id, $testimonial->client->_id, 'replied to your testimonial', strval(url('testimonial', 'on', $profile->getUsername())->absolute()));
			}
			
			$this->response->setBody('Redirect')->getHeaders()->redirect(url('testimonial', 'on', $profile->getUsername())->absolute());
			return;
		} 
		catch (HTTPMethodException$ex) {

		}
		
		$this->view->set('testimonial', $testimonial);
		$this->view->set('profile', $profile);
	}
	
}
