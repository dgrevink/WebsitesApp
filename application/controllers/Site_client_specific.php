<?php

$slides = MyActiveRecord::FindAll('slider', "language = '{$this->language}'", 'porder asc');
$code = '';
foreach($slides as $slide) {
	$code .= "<li>";
	$code .= "<div class='text'><h2>$slide->title</h2>";
	$code .= "$slide->description</div>";
	$code .= "<img class='illustration' src='$slide->image' alt='$slide->title'/>";
	$code .= "<a class='link' href='{$slide->link}'>{$slide->link_title}</a>";
	$code .= "</li>";
}
$this->template->assign('slider', $code);
