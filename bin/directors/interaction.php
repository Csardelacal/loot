<?php

use spitfire\exceptions\PrivateException;
use spitfire\mvc\Director;

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

class InteractionDirector extends Director
{
	
	/**
	 * Processes interactions that the system has received. It then assigns quests,
	 * and rewards to the interactions and gives the user score and badges.
	 * 
	 * @throws PrivateException If a database error occurred.
	 */
	public function process() {
		/*
		 * Select all interactions that have not yet been processed. We limit this
		 * query to prevent the system from being overwhelmed by really high spikes
		 * in activity.
		 * 
		 * TODO: It would be better if the limit was configurable.
		 */
		$pending = db()->table('interaction')->get('processed', null)->range(0, 20000);
		
		/*
		 * Loop over each interaction that has not yet been processed.
		 */
		foreach ($pending as $interaction) {
			
			/*
			 * Fetch the quests that relate to this interaction and loop over those
			 * to detect whether a user deserves a badge for their track record.
			 */
			$quests = db()->table('quest')->get('activityName', $interaction->name)->all();
			
			foreach ($quests as $quest) {
				/*
				 * Find previous interactions that match the activity name. This way we can
				 * determine whether the user has the necessary interactions to receive
				 * a badge.
				 */
				$query = db()->table('interaction')->get('name', $quest->activityName)->where('tgt', $interaction->tgt);
				if ($quest->ttl) { $query->where('created', '>', time() - $quest->ttl); }
				
				$query->setOrder('created', 'DESC');
				
				/*
				 * We limit the amount of results to the maximum that the element supports.
				 * It's basically pointless to process more than thirty items if the 
				 * quest only requires 30.
				 */
				$previous = $query->all();
				
				/*
				 * Depending on whether the quest expects the user to have a certain
				 * total value or a certain amount of interactions, we will act differently.
				 * 
				 * In this case, if the application expects a certain value to be met,
				 * the application loops over all the items until the value it expects
				 * is satisfied.
				 */
				if ($quest->perValue) {
					$sum = 0;
					$achieved = false;
					
					foreach ($previous as $current) {
						$sum+= $current->value;
						if ($sum > $quest->threshold) {
							$achieved = true;
							$last = $current;
							break;
						}
					}
				}
				
				/*
				 * On the other hand, if the application expects a certain number of 
				 * interactions, the application can check whether the count is correct.
				 */
				else {
					$achieved = $previous->count() >= $quest->threshold;
					$last = $previous->has($quest->threshold)? $previous[$quest->threshold] : null;
				}
				
				/*
				 * If the user meets the criteria (or has been meeting it), we will
				 * write to the database, so the system can later retrieve the information
				 * and provided to users who wish to know whether the user is reputable.
				 */
				if ($achieved) {
					$badge = db()->table('badge')->get('user', $interaction->tgt)->where('quest', $quest)->first()?: db()->table('badge')->newRecord();
					$badge->quest   = $quest;
					$badge->user    = $interaction->tgt;
					$badge->expires = $last->created + $quest->ttl;
					$badge->store();
				}
			}
			
			/*
			 * Distribute the score rewards. The score is just the amount of points
			 * the user received for their behavior on site and has no additional
			 * metadata.
			 */
			$rewards = db()->table('reward')->get('activityName', $interaction->name)->all();
			
			foreach ($rewards as $reward) {
				
				if ($reward->awardTo == RewardModel::AWARDTO_SOURCE) {
					$record = db()->table('score')->newRecord();
					$record->interaction = $interaction;
					$record->user = $interaction->src;
					$record->rule = $reward;
					$record->score = $reward->score * ($reward->perValue? $interaction->value : 1);
					$record->created = time();
					$record->user && $record->store();
				}
				
				if ($reward->awardTo == RewardModel::AWARDTO_TARGET) {
					$record = db()->table('score')->newRecord();
					$record->interaction = $interaction;
					$record->user = $interaction->tgt;
					$record->rule = $reward;
					$record->score = $reward->score * ($reward->perValue? $interaction->value : 1);
					$record->created = time();
					$record->user && $record->store();
				}
				
			}
			
			$interaction->processed = time();
			$interaction->store();
			
			console()->success('Processed interaction')->ln();
			
		}
	}
	
}
