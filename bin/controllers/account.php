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

class AccountController extends BaseController
{
	
	public function login() {
		$rtt = isset($_GET['returnto']) && Strings::startsWith($_GET['returnto'], '/') && !Strings::startsWith($_GET['returnto'], '//');
		
		if ($this->user) {
			return $this->response->setBody('Redirecting...')
					->getHeaders()->redirect($rtt? $_GET['returnto'] : url());
		}
		
		$this->session->lock($token = $this->sso->createToken(7 * 86400));
		
		echo $this->token->getId(), '<br>';
		die($token->getRedirect((string) spitfire\core\http\AbsoluteURL::current()));
		
		$this->response->setBody('Redirection...')->getHeaders()->redirect($token->getRedirect((string) spitfire\core\http\AbsoluteURL::current()));
		return;
	}
}
