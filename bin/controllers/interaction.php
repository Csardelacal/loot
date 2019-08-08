<?php

use spitfire\exceptions\PublicException;

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

class InteractionController extends BaseController
{
	
	/**
	 * This action allows applications on the network to submit new entries to the
	 * user's activity.
	 * 
	 * @request-method POST
	 * 
	 * @validate POST#target(positive number required)
	 * @validate POST#source(positive number required)
	 * 
	 * @validate POST#name(required string)
	 * @validate POST#value(number)
	 * 
	 * @validate POST#caption (required string)
	 * @validate POST#url (string url)
	 * 
	 */
	public function push() {
		
		/*
		 * Check if there is an application context, and only an application context.
		 * Users shouldn't be allowed to modify their own reputation.
		 */
		if (!$this->authapp || $this->user) {
			throw new PublicException('Users are not allowed to push interactions. Please do so from an application', 403);
		}
		
		/*
		 * Retrieve the source from the request. This is the user where the interaction
		 * stems from.
		 */
		$src = db()->table('user')->get('_id', $_POST['source'])->first();
		if (!$src) { $src = UserModel::make($this->sso->getUser($_POST['source'])); }
		
		/*
		 * Get the user that received the interaction.
		 */
		$tgt = db()->table('user')->get('_id', $_POST['target'])->first();
		if (!$tgt) { $src = UserModel::make($this->sso->getUser($_POST['target'])); }
		
		if (!$src || !$tgt) {
			throw new PublicException('Could not find users', 400);
		}
		
		/*
		 * Create the record and store it to the database.
		 */
		$record = db()->table('interaction')->newRecord();
		$record->src = $src;
		$record->tgt = $tgt;
		$record->name = $_POST['name'];
		$record->value = $_POST['value'];
		$record->caption = $_POST['caption'];
		$record->url = $_POST['url'];
		$record->created = time();
		$record->store();
		
		/*
		 * Pass the data onto the view, so the application sending the request can
		 * parse it and extract data from it.
		 */
		$this->view->set('record', $record);
	}
	
}
