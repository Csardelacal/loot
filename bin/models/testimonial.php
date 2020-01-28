<?php

use spitfire\Model;
use spitfire\storage\database\Schema;

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
 * Testimonials allow other users to add information on why dealing with a certain
 * user is recommendable or not. This provides a layer of depth to a user's profile.
 * 
 * @property int $user The user id of the user receiving the review
 * @property int $client The id of the user posting the review
 * @property boolean $recommendation Whether the client recommends the user.
 * @property string $body The message the client added to their testimonial
 * @property string $response The response of the user
 * @property string $product URL with information over the product / transaction the client bought
 * @property int $created Timestamp this record was created
 * 
 * @author César de la Cal Bretschneider <cesar@magic3w.com>
 */
class TestimonialModel extends Model
{
	
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		$schema->user = new Reference('user');
		
		$schema->client = new Reference('user');
		
		/*
		 * Whether the testimonial recommends interacting with the seller to other
		 * potential clients or buyers.
		 */
		$schema->recommendation = new BooleanField();
		
		/*
		 * Allow the buyer to add information on why the do or do not recommend the
		 * seller to potential interested buyers.
		 */
		$schema->body = new TextField();
		
		/*
		 * Allow the seller to respond to the testimonial. Sometimes a seller wishes
		 * to provide the user / potential interested users with clarification on 
		 * a testimonial.
		 */
		$schema->response = new TextField();
		
		/*
		 * The URL of the product that the user bought, or to a page with the information
		 * of the service the customer paid for.
		 */
		$schema->product = new StringField(512);
		
		/*
		 * UNIX Timestamp this record was created at. We can also use this to expire
		 * old testimonials that do not properly reflect the current state of the 
		 * account.
		 */
		$schema->created  = new IntegerField(true);
		
		$schema->index($schema->user, $schema->created);
	}
	
	public function onbeforesave() {
		/*
		 * If the record is new, then we add the timestamp, this allows the system
		 * to present relative times next to the redcord.
		 */
		if (!$this->created) {
			$this->created = time();
		}
	}

}
