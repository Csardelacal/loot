<?php

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

current_context()->response->getHeaders()->set('Access-Control-Allow-Origin', '*');
current_context()->response->getHeaders()->set('Access-Control-Allow-Headers', 'Content-type');
current_context()->response->getHeaders()->contentType('json');

echo json_encode([
	'result' => 'success',
	'payload' => [
		'id' => $profile->getId(),
		'name' => $profile->getUsername(),
		'url'  => (string)url('user', 'profile', $profile->getUsername())->absolute(),
		'score' => $score,
		'approval' => $approval,
		'badges' => collect($badges)->each(function ($e) { return [ 'id' => $e->quest->_id, 'name' => $e->quest->name, 'color' => $e->quest->color ]; })->toArray(),
		'testimonials' => collect($testimonials)->each(function ($e) use ($sso) { return [
			'id' => $e->_id, 
			'from' => [ 'id' => $e->client->_id, 'username' => $sso->getUser($e->client->_id)->getUsername(), 'avatar' => $sso->getUser($e->client->_id)->getAvatar(256)], 
			'recommendation' => $e->recommendation, 
			'created' => $e->created,
			 'body' => $e->body 
		]; })->toArray()
	]
]);