<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\comment;

/**
 *
 * @author 13dagger
 */
interface iCommentAttach {

	/**
	 * get relative url to file
	 */
	public function getUrl();

	/**
	 * get absolute filename
	 */
	public function getAbsFn();
}
