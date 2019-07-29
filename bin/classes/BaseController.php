<?php

use auth\SSO;
use auth\SSOCache;
use auth\Token;
use spitfire\cache\MemcachedAdapter;
use spitfire\core\Environment;
use spitfire\io\session\Session;

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

class BaseController extends Controller
{
	
	protected $session;
	
	/**
	 *
	 * @var SSO
	 */
	protected $sso;
	
	protected $user;
	
	protected $authapp;
	
	protected $ping;
	
	public function _onload() {
		
		$session = $this->session = Session::getInstance();
		
		#Create a brief cache for the sessions.
		$cache       = new MemcachedAdapter();
		$cache->setTimeout(120);
		
		#Create a user
		$this->sso     = new SSOCache(Environment::get('SSO'));
		$this->token   = isset($_GET['token'])? $this->sso->makeToken($_GET['token']) : $session->getUser();
		
		#Fetch the user from the cache if necessary
		$this->user  = $this->token && $this->token instanceof Token? $cache->get('loot_token_' . $this->token->getId(), function () { 
			return $this->token->isAuthenticated()? $this->token->getTokenInfo()->user : null; 
		}) : null;
		
		$this->authapp = isset($_GET['signature'])? $this->sso->authApp($_GET['signature']) : 
			($this->user? $cache->get('loot_authapp_' . $this->token->getId(), function () { 
				return $this->token->getTokenInfo()->app->id; 
			}) : null);
			
		$this->ping = new ping\Ping(Environment::get('ping'), $this->sso);
		
		#Maintain the user in the view. This way we can draw an interface for them
		$this->view->set('authUser', $this->user);
		$this->view->set('authToken', $this->token);
		$this->view->set('sso', $this->sso);
		$this->view->set('ping', $this->ping);
	}
	
}
